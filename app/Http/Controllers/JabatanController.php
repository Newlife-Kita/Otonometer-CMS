<?php

namespace App\Http\Controllers;

use App\DataTables\JabatanDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateJabatanRequest;
use App\Http\Requests\UpdateJabatanRequest;
use App\Repositories\JabatanRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BahasaRepository;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel; 

class JabatanController extends AppBaseController
{
    /** @var  JabatanRepository */
    private $jabatanRepository;
    private $bahasaRepository;

    public function __construct(JabatanRepository $jabatanRepo, BahasaRepository $bahasaRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:jabatan-edit', ['only' => ['edit']]);
        $this->middleware('can:jabatan-store', ['only' => ['store']]);
        $this->middleware('can:jabatan-show', ['only' => ['show']]);
        $this->middleware('can:jabatan-update', ['only' => ['update']]);
        $this->middleware('can:jabatan-delete', ['only' => ['delete']]);
        $this->middleware('can:jabatan-create', ['only' => ['create']]);
        $this->jabatanRepository = $jabatanRepo;
        $this->bahasaRepository = $bahasaRepo;
    }

    /**
     * Display a listing of the Jabatan.
     *
     * @param JabatanDataTable $jabatanDataTable
     * @return Response
     */
    public function index(JabatanDataTable $jabatanDataTable)
    {
        $akses = Auth::user();
        if(!$akses->can('jabatan-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return $jabatanDataTable->render('jabatans.index');
    }

    /**
     * Show the form for creating a new Jabatan.
     *
     * @return Response
     */
    public function create()
    {
        $akses = Auth::user();
        if(!$akses->can('jabatan-create')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $bahasa = $this->bahasaRepository->all();
        return view('jabatans.create')->with('urutan', ['' => 'Pilih Urutan'])->with('bahasa', $bahasa);
    }

    /**
     * Store a newly created Jabatan in storage.
     *
     * @param CreateJabatanRequest $request
     *
     * @return Response
     */
    public function store(CreateJabatanRequest $request)
    {
        $input = $request->all();
        
        /** Proses Kode disini */
        $last_increament = $this->jabatanRepository->where('tipe', @$input["tipe"])->max('id_increament');
        $next_inc = intval(@$last_increament)+1;

        $input['id_increament'] = $next_inc;
        $input['kode'] = $input['tipe'].'.'.$next_inc;

        if(intval(@$input["urutan"]) > 0){
            $data_urut = $this->jabatanRepository->findWithoutFail(@$input["urutan"]);
            @$input["urutan"] = @$data_urut->urutan;
        }
        else{
            @$input["urutan"] = 0;
        }

        $jabatan = $this->jabatanRepository->create($input);     

        if(@$jabatan->id > 0){            
            $this->reorder_sortdata(@$input["urutan"], $jabatan);
        }
        
        Flash::success('Jabatan Pemerintahan saved successfully.');
        return redirect(route('jabatans.index'));
    }

    /**
     * Display the specified Jabatan.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id, Request $request)
    {
        $akses = Auth::user();
        if(!$akses->can('jabatan-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $input = $request->all();
        $id = intval(@$input['id']);
        $type = @$input['type'];
        $type_wilayah = @$input['type_wilayah'];
            
        $items = $this->jabatanRepository->where('tipe', $type)->where('tipe_wilayah', $type_wilayah)
        ->where(function($query) use ($id){
            return $query->where('id', '!=', $id);
        })->orderBy('urutan', 'asc')->selectRaw('id,nama,urutan')->get();
        return response()->json(['items' => $items->toArray()]); 
    }

    /**
     * Show the form for editing the specified Jabatan.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $akses = Auth::user();
        if(!$akses->can('jabatan-edit')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $jabatan = $this->jabatanRepository->findWithoutFail($id);

        if (empty($jabatan)) {
            Flash::error('Jabatan Pemerintahan not found');
            return redirect(route('jabatans.index'));
        }

        $urutan = $this->jabatanRepository->where('tipe', $jabatan->tipe)->where('tipe_wilayah', $jabatan->type_wilayah)
        ->where(function($query) use ($jabatan){
            return $query->where('id', '!=', $jabatan->id);
        })->orderBy('urutan', 'asc')->selectRaw('id, CONCAT_WS("Setelah ",nama) as nama')->get();

        $arr_urut = ['-1' => 'Urutan Pertama'];
        if($urutan->count() > 0){
            foreach($urutan as $row){
                $arr_urut[$row->id] = 'Setelah ' . $row->nama;
            }
        }        
        $bahasa = $this->bahasaRepository->all();

        return view('jabatans.edit')
            ->with('jabatan', $jabatan)
            ->with('urutan', $arr_urut)->with('bahasa', $bahasa);
    }

    /**
     * Update the specified Jabatan in storage.
     *
     * @param  int              $id
     * @param UpdateJabatanRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateJabatanRequest $request)
    {
        $jabatan = $this->jabatanRepository->findWithoutFail($id);

        if (empty($jabatan)) {
            Flash::error('Jabatan Pemerintahan not found');
            return redirect(route('jabatans.index'));
        }

        $input = $request->all();
        $id_urutan = intval(@$input["urutan"]);
        
        $last_order = 0;
        if($id_urutan > 0){
            $data_urut = $this->jabatanRepository->findWithoutFail(@$id_urutan);
            @$input["urutan"] = intval(@$data_urut->urutan);
            $last_order = @$data_urut->urutan;
        }
        else{
            @$input["urutan"] = 0;
        }
        
        $jabatan = $this->jabatanRepository->update($input, $id);
        
        $this->reorder_sortdata($last_order, $jabatan);

        Flash::success('Jabatan Pemerintahan updated successfully.');
        return redirect(route('jabatans.index'));
    }

    function reorder_sortdata($last_order, $data){

        if(intval($last_order) > 0){
            $all_data = $this->jabatanRepository->where('tipe', @$data->tipe)->where('tipe_wilayah', $data->type_wilayah)->selectRaw('id,nama,urutan')->orderBy('urutan','asc')->orderBy('updated_at','asc')->get();  
            $next_sort = 0;          
        }
        else{                    
            $all_data = $this->jabatanRepository->where('tipe', @$data->tipe)->where('tipe_wilayah', $data->type_wilayah)->selectRaw('id,nama,urutan')->where('id', '!=', $data->id)->orderBy('urutan','asc')->orderBy('updated_at','asc')->get();
            $next_sort = 1;
        }

        if($all_data->count() > 0){
            foreach($all_data as $dl){
                $this->jabatanRepository->update(['urutan' => $next_sort], $dl->id);                    
                $next_sort++;
            }
        }  
    }

    /**
     * Remove the specified Jabatan from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $jabatan = $this->jabatanRepository->findWithoutFail($id);

        if (empty($jabatan)) {
            Flash::error('Jabatan Pemerintahan not found');
            return redirect(route('jabatans.index'));
        }

        $this->jabatanRepository->delete($id);

        Flash::success('Jabatan Pemerintahan deleted successfully.');
        return redirect(route('jabatans.index'));
    }

    /**
     * Store data Jabatan from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $jabatan = $this->jabatanRepository->create($item->toArray());
            });
        });

        Flash::success('Jabatan Pemerintahan saved successfully.');
        return redirect(route('jabatans.index'));
    }
}
