<?php

namespace App\Http\Controllers;

use App\DataTables\KodeposDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateKodeposRequest;
use App\Http\Requests\UpdateKodeposRequest;
use App\Repositories\KodeposRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\WilayahRepository;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel; 

class KodeposController extends AppBaseController
{
    /** @var  KodeposRepository */
    private $kodeposRepository;
    private $wilayahRepository;

    public function __construct(KodeposRepository $kodeposRepo, WilayahRepository $wilayahRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:kodepos-edit', ['only' => ['edit']]);
        $this->middleware('can:kodepos-store', ['only' => ['store']]);
        $this->middleware('can:kodepos-show', ['only' => ['show']]);
        $this->middleware('can:kodepos-update', ['only' => ['update']]);
        $this->middleware('can:kodepos-delete', ['only' => ['delete']]);
        $this->middleware('can:kodepos-create', ['only' => ['create']]);
        $this->kodeposRepository = $kodeposRepo;
        $this->wilayahRepository = $wilayahRepo;
    }

    /**
     * Display a listing of the Kodepos.
     *
     * @param KodeposDataTable $kodeposDataTable
     * @return Response
     */
    public function index(KodeposDataTable $kodeposDataTable)
    {
        $akses = Auth::user();
        if(!$akses->can('kodepos-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return $kodeposDataTable->render('kodepos.index');
    }

    /**
     * Show the form for creating a new Kodepos.
     *
     * @return Response
     */
    public function create()
    {
        $akses = Auth::user();
        if(!$akses->can('kodepos-create')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return view('kodepos.create');
    }

    /**
     * Store a newly created Kodepos in storage.
     *
     * @param CreateKodeposRequest $request
     *
     * @return Response
     */
    public function store(CreateKodeposRequest $request)
    {
        $input = $request->all();
        $kecamatan = $this->kodeposRepository->where('type','kecamatan')->where('id_wilayah',@$input["id_wilayah"])->where('nama',@$input["nama"])->first();

        if($kecamatan) {
            return redirect()->back()->withInput($input)->withErrors(['Kecamatan '.@$input["nama"].' sudah ada di database']);
        }

        $kecamatan = $this->kodeposRepository->create([
            'id_wilayah' => @$input["id_wilayah"],	
            'type' =>'kecamatan',
            'kodepos' => @$input["kodepos"],	
            'nama' => @$input["nama"],
        ]);

        if(intval(@$kecamatan->id) > 0){
            foreach(@$input["kel_id"] as $key => $val){
                if(strlen(@$input["kel_name"][$key]) > 1){
                    $this->kodeposRepository->create([
                        'id_wilayah' => @$input["id_wilayah"],	
                        'type' =>'kelurahan',
                        'id_parent' => @$kecamatan->id,
                        'kodepos' => @$input["kel_kodepos"][$key],	
                        'nama' => @$input["kel_name"][$key],
                    ]);
                }
            }
        }
        Flash::success('Kecamatan Berhasil disimpan');
        return redirect(route('kodepos.index'));
    }

    /**
     * Display the specified Kodepos.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        return redirect(route('kodepos.index'));
        
        $akses = Auth::user();
        if(!$akses->can('kodepos-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $kodepos = $this->kodeposRepository->findWithoutFail($id);

        if (empty($kodepos)) {
            Flash::error('Kodepos not found');
            return redirect(route('kodepos.index'));
        }

        return view('kodepos.show')->with('kodepos', $kodepos);
    }

    /**
     * Show the form for editing the specified Kodepos.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $akses = Auth::user();
        if(!$akses->can('kodepos-edit')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $kecamatan = $this->kodeposRepository->findWithoutFail($id);

        if (empty($kecamatan)) {
            Flash::error('Kecamatan Not Found');
            return redirect(route('kodepos.index'));
        }

        if ($kecamatan->type == 'kelurahan') {
            Flash::error('Invalid Kecamatan');
            return redirect(route('kodepos.index'));
        }

        $kelurahan = $this->kodeposRepository->where('id_parent',$id)->orderBy('kodepos','asc')->get();
        $kota = $this->wilayahRepository->findWithoutFail($kecamatan->id_wilayah);
        return view('kodepos.edit')->with('kota', $kota)->with('kecamatan', $kecamatan)->with('kelurahan', $kelurahan);
    }

    /**
     * Update the specified Kodepos in storage.
     *
     * @param  int              $id
     * @param UpdateKodeposRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateKodeposRequest $request)
    {
        $kecamatan = $this->kodeposRepository->findWithoutFail($id);

        if (empty($kecamatan)) {
            Flash::error('Kecamatan Not Found');
            return redirect(route('kodepos.index'));
        }

        if ($kecamatan->type == 'kelurahan') {
            Flash::error('Invalid Kecamatan');
            return redirect(route('kodepos.index'));
        }

        $input = $request->all();
        
        $kecamatan = $this->kodeposRepository->update([
            'id_wilayah' => @$input["id_wilayah"],	
            'type' =>'kecamatan',
            'kodepos' => @$input["kodepos"],	
            'nama' => @$input["nama"],
        ], $id);

        foreach(@$input["kel_id"] as $key => $val){
            if($val > 0) $this->kodeposRepository->update([
                    'id_wilayah' => @$input["id_wilayah"],
                    'kodepos' => @$input["kel_kodepos"][$key],	
                    'nama' => @$input["kel_name"][$key],
                ], $val);
            else $this->kodeposRepository->create([
                    'id_wilayah' => @$input["id_wilayah"],	
                    'type' =>'kelurahan',
                    'id_parent' => $id,
                    'kodepos' => @$input["kel_kodepos"][$key],	
                    'nama' => @$input["kel_name"][$key],
                ]);
        }

        Flash::success('Kodepos berhasil disimpan.');
        return redirect(route('kodepos.index'));
    }

    /**
     * Remove the specified Kodepos from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $kecamatan = $this->kodeposRepository->findWithoutFail($id);

        if (empty($kecamatan)) {
            Flash::error('Kecamatan Not Found');
            return redirect(route('kodepos.index'));
        }

        if ($kecamatan->type == 'kelurahan') {
            Flash::error('Invalid Kecamatan');
            return redirect(route('kodepos.index'));
        }

        $this->kodeposRepository->whereId($id)->forceDelete();
        $this->kodeposRepository->where('id_parent',$id)->forceDelete();

        Flash::success('Kecamatan '.@$kecamatan->nama.' deleted successfully.');
        return redirect(route('kodepos.index'));
    }

    public function destroy_kelurahan(Request $request)
    {
        $id = @$request->id;
        $kodepos = $this->kodeposRepository->findWithoutFail(@$id);

        if (empty($kodepos)) {            
            return response()->json(['valid' => false, 'message' => 'Kelurahan tidak ditemukan!']);
        }
        if ($kodepos->type == 'kecamatan') {
            return response()->json(['valid' => false, 'message' => 'Invalid Kelurahan!']);
        }

        $this->kodeposRepository->whereId($id)->forceDelete();
        return response()->json(['valid' => true, 'message' => 'Kelurahan berhasil di hapus']);
    }
}
