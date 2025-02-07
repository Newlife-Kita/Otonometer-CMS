<?php

namespace App\Http\Controllers;

use App\DataTables\NomenklaturDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateNomenklaturRequest;
use App\Http\Requests\UpdateNomenklaturRequest;
use App\Repositories\NomenklaturRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Models\Bidang;
use App\Models\Nomenklatur;
use App\Models\Setting;
use App\Repositories\BahasaRepository;
use App\Repositories\BidangRepository;
use App\Repositories\NoteRepository;
use App\Repositories\SatuanRepository;
use App\Repositories\SumberdataRepository;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class NomenklaturController extends AppBaseController
{
    /** @var  NomenklaturRepository */
    private $nomenklaturRepository;
    private $bidangRepository;
    private $bahasaRepository;
    private $satuanRepository;
    private $noteRepository;
    private $sumberRepository;

    public function __construct(NomenklaturRepository $nomenklaturRepo, BidangRepository $bidangRepo, BahasaRepository $bahasaRepo, SatuanRepository $satuanRepo, NoteRepository $noteRepo, SumberdataRepository $sumberRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:nomenklatur-edit', ['only' => ['edit']]);
        $this->middleware('can:nomenklatur-store', ['only' => ['store']]);
        $this->middleware('can:nomenklatur-show', ['only' => ['show']]);
        $this->middleware('can:nomenklatur-update', ['only' => ['update']]);
        $this->middleware('can:nomenklatur-delete', ['only' => ['delete']]);
        $this->middleware('can:nomenklatur-create', ['only' => ['create']]);
        $this->nomenklaturRepository = $nomenklaturRepo;
        $this->bidangRepository = $bidangRepo;
        $this->bahasaRepository = $bahasaRepo;
        $this->satuanRepository = $satuanRepo;
        $this->noteRepository = $noteRepo;
        $this->sumberRepository = $sumberRepo;
    }

    //Done
    public function index(NomenklaturDataTable $nomenklaturDataTable)
    {
        $akses = Auth::user();
        if(!$akses->can('nomenklatur-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return $nomenklaturDataTable->render('nomenklaturs.index');
    }

    //Done
    public function create()
    {
        $akses = Auth::user();
        if(!$akses->can('nomenklatur-create')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $keuangan = $this->bidangRepository->get_sektor_table(1);
        $ekonomi = $this->bidangRepository->get_sektor_table(2);
        $statistik = $this->bidangRepository->get_sektor_table(3);
        return view('nomenklaturs.create')->with('bidang', ['keuangan' => $keuangan, 'ekonomi' => $ekonomi, 'statistik' => $statistik]);
    }

    //Done
    public function store(Request $request){
        $input = $request->all();
        $tahun = @$input['tahun_nomenklatur'];
        $description = @$input['description'];
        $selected = @$input['use_row'];

        if(count($selected) > 0){
            foreach($selected as $chk){
                $thn = '['. @$input['year_row'][$chk].','.$tahun.']';
                $this->bidangRepository->update(['tahun_nomenklatur' => $thn], $chk);
            }
        }
         
        $this->nomenklaturRepository->create(['kode' => $tahun, 'tahun_nomenklatur' => $tahun, 'nama' => $description, 'description' => $description]);        
        
        Flash::success('Tahun Nomenklatur successfully saved');
        return redirect(route('nomenklaturs.index'));
    }

    //Done
    /**
     * Show the form for editing the specified Nomenklatur.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $akses = Auth::user();
        if(!$akses->can('nomenklatur-edit')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $nomenklatur = $this->nomenklaturRepository->findWithoutFail($id);
        if (empty($nomenklatur)) {
            Flash::error('Tahun Nomenklatur not found');
            return redirect(route('nomenklaturs.index'));
        }

        $keuangan = $this->bidangRepository->get_sektor_table(1);
        $ekonomi = $this->bidangRepository->get_sektor_table(2);
        $statistik = $this->bidangRepository->get_sektor_table(3);

        return view('nomenklaturs.edit')->with('bidang', ['keuangan' => $keuangan, 'ekonomi' => $ekonomi, 'statistik' => $statistik])->with('nomenklatur', $nomenklatur);        
    }

    //Done
    /**
     * Update the specified Nomenklatur in storage.
     *
     * @param  int              $id
     * @param UpdateNomenklaturRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $nomenklatur = $this->nomenklaturRepository->findWithoutFail($id);
        if (empty($nomenklatur)) {
            Flash::error('Tahun Nomenklatur not found');
            return redirect(route('nomenklaturs.index'));
        }

        $input = $request->all();
        $tahun = @$input['tahun_nomenklatur'];
        $description = @$input['description'];
        $selected = @$input['use_row'];

        $bidang = $this->nomenklaturRepository->get_array_bidang_all();
        if(count($bidang) > 0){
            foreach($bidang as $bid){
                $bid['tahun_nomenklatur'] = ltrim($bid['tahun_nomenklatur'],'[');
                $bid['tahun_nomenklatur'] = rtrim($bid['tahun_nomenklatur'],']');
                $bid['tahun_nomenklatur'] = explode(',',$bid['tahun_nomenklatur']);
                $bid['tahun_nomenklatur'] = array_diff($bid['tahun_nomenklatur'], array($nomenklatur->kode));
                
                if(in_array($bid['id'], $selected, false)) $bid['tahun_nomenklatur'] = array_merge(array($tahun), $bid['tahun_nomenklatur']);

                $bid['tahun_nomenklatur'] = array_unique($bid['tahun_nomenklatur']);
                asort($bid['tahun_nomenklatur']);
                $bid['tahun_nomenklatur'] = '['. implode(',', $bid['tahun_nomenklatur']).']';

                $this->bidangRepository->update(['tahun_nomenklatur' => $bid['tahun_nomenklatur']], $bid['id']);
            }
        }
        
        $this->nomenklaturRepository->update(['kode' => $tahun, 'tahun_nomenklatur' => $tahun, 'nama' => $description, 'description' => $description], $id);  
        
        Flash::success('Tahun Nomenklatur updated successfully.');
        return redirect(route('nomenklaturs.index'));
    }
    
    //Done
    public function destroy($id)
    {
        $nomenklatur = $this->nomenklaturRepository->findWithoutFail($id);
        $bidang = $this->nomenklaturRepository->get_array_bidang_all();
        
        if(count($bidang) > 0){
            foreach($bidang as $bid){
                $bid['tahun_nomenklatur'] = ltrim($bid['tahun_nomenklatur'],'[');
                $bid['tahun_nomenklatur'] = rtrim($bid['tahun_nomenklatur'],']');
                $bid['tahun_nomenklatur'] = explode(',',$bid['tahun_nomenklatur']);
                $bid['tahun_nomenklatur'] = array_diff($bid['tahun_nomenklatur'], array($nomenklatur->kode));
                asort($bid['tahun_nomenklatur']);
                $bid['tahun_nomenklatur'] = '['. implode(',', $bid['tahun_nomenklatur']).']';
                $this->bidangRepository->update(['tahun_nomenklatur' => $bid['tahun_nomenklatur']], $bid['id']);
            }
        }
        $this->nomenklaturRepository->whereId($id)->forceDelete();

        Flash::success('Tahun Nomenklatur deleted successfully.');
        return redirect(route('nomenklaturs.index'));
    }

    /**
     * Remove the specified Nomenklatur from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroysektor($tahun, $id, Request $request)
    {
        $nomenklatur = $this->bidangRepository->findWithoutFail($id);
        $parent = $this->bidangRepository->findWithoutFail($nomenklatur->id_parent);

        if (empty($nomenklatur)) {
            return response()->json(['valid' => false, 'message' => 'Sektor/Bidang not found'], 200);
        }

        if (empty($parent)) {
            return response()->json(['valid' => false, 'message' => 'Sektor/Bidang Tahun not found'], 200);
        }

        if ($nomenklatur->level == '1') {
            return response()->json(['valid' => false, 'message' => 'This data could not be deleted'], 200);
        }

        $tahun_str = $nomenklatur->tahun_nomenklatur;
        $tahun_1 = ltrim($tahun_str, '[');
        $tahun_2 = rtrim($tahun_1, ']');
        $tahun_arr = explode(',', $tahun_2);
        $new_tahun = '[';
        if(count($tahun_arr) > 1){            
            foreach($tahun_arr as $thn){
                if($thn != $tahun) $new_tahun .= $thn. ',';
            }
            $new_tahun = rtrim($new_tahun, ',');
            $new_tahun .= ']';
            $message = 'Sektor/Bidang Nomenklatur Tahun '. $tahun . ' Successfully Deleted';
            $this->bidangRepository->update(['tahun_nomenklatur' => $new_tahun], $id);
        }
        else{
            if($tahun_arr[0] == $tahun){
                $message = 'Sektor/Bidang Nomenklatur Tahun '. $tahun . ' Successfully Deleted';
                $this->bidangRepository->delete($id);
            }
        }
        return response()->json(['valid' => true, 'message' => $message], 200);
    }
}
