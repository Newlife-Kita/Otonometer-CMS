<?php

namespace App\Http\Controllers\Panel;

use App\DataTables\Panel\KodeposDataTable;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\WilayahRepository;
use App\Repositories\DataranRepository;
use App\Repositories\KodeposRepository;
use Response;
use Illuminate\Support\Facades\Auth;
use App\Services\UploadFileService;
use Illuminate\Http\Request;

class KodeposController extends AppBaseController
{
    /** @var  WilayahRepository */
    private $wilayahRepository;
    private $kodeposRepository;

    public function __construct(WilayahRepository $wilayahRepo, KodeposRepository $kodeposRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:kodepos-edit', ['only' => ['edit']]);
        $this->middleware('can:kodepos-store', ['only' => ['store']]);
        $this->middleware('can:kodepos-show', ['only' => ['show']]);
        $this->middleware('can:kodepos-update', ['only' => ['update']]);
        $this->middleware('can:kodepos-delete', ['only' => ['delete']]);
        $this->middleware('can:kodepos-create', ['only' => ['create']]);
        $this->wilayahRepository = $wilayahRepo;
        $this->kodeposRepository = $kodeposRepo;
    }

    /**
     * Display a listing of available Provinces.
     *
     * @param WilayahDataTable $wilayahDataTable
     * @return Response
     */
    public function index(KodeposDataTable $kodeposdataTable)
    {
        $akses = Auth::user();
        if(!$akses->can('kodepos-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        /** Panel Admin Wilayah Akses */
        check_panel_access();

        $wilayah = $this->wilayahRepository->findWithoutFail($akses->id_wilayah);
        if(empty($wilayah)){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        if($akses->id_wilayah != $wilayah->id){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        
        if($wilayah->tipe == 'propinsi'){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        else{
            $data = [
                'wilayah' => $wilayah
            ];      
            return $kodeposdataTable->setWilayah($akses->id_wilayah)->renders('admin_panel.kodepos.index_kecamatan',[],[],$data);
        }
    }

    /**
     * Show the form for editing the specified Bahasa.
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
        /** Panel Admin Wilayah Akses */
        check_panel_access();

        $wilayah = $this->wilayahRepository->findWithoutFail($akses->id_wilayah);
        if(empty($wilayah)){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        $kecamatan = $this->kodeposRepository->findWithoutFail($id);

        if (empty($kecamatan)) {
            Flash::error('Kecamatan not found');
            return redirect(route('panel.kodepos.index'));
        }

        $kelurahan = $this->kodeposRepository->where('type', 'kelurahan')->where('id_parent', $kecamatan->id)->get();
        return view('admin_panel.kodepos.edit')->with('wilayah', $wilayah)->with('kecamatan', $kecamatan)->with('kelurahan', $kelurahan);
    }

    public function update(Request $request){
        $input = $request->all();
        
        $akses = Auth::user();
        if(!$akses->can('kodepos-update')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        /** Panel Admin Wilayah Akses */
        check_panel_access();

        $wilayah = $this->wilayahRepository->findWithoutFail($akses->id_wilayah);
        if(empty($wilayah)){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        if($akses->id_wilayah != $wilayah->id){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        
        $kecamatan = $this->kodeposRepository->findWithoutFail(@$input["id"]);

        if (empty($kecamatan)) {
            Flash::error('Kecamatan not found');
            return redirect(route('panel.kodepos.index'));
        }

        if ($kecamatan->type == 'kelurahan') {
            Flash::error('Invalid Kecamatan');
            return redirect(route('kodepos.index'));
        }

        // Update Kecamatan
        $kecamatan = $this->kodeposRepository->update([
            'id_wilayah' => @$input["id_wilayah"],	
            'type' =>'kecamatan',
            'kodepos' => @$input["kodepos"],	
            'nama' => @$input["nama"],
        ], @$input["id"]);

        // update kelurahan        
        foreach(@$input["kel_id"] as $key => $val){
            if($val > 0) $this->kodeposRepository->update([
                    'id_wilayah' => @$input["id_wilayah"],
                    'kodepos' => @$input["kel_kodepos"][$key],	
                    'nama' => @$input["kel_name"][$key],
                ], $val);
            else $this->kodeposRepository->create([
                    'id_wilayah' => @$input["id_wilayah"],	
                    'type' =>'kelurahan',
                    'id_parent' => @$input["id"],
                    'kodepos' => @$input["kel_kodepos"][$key],	
                    'nama' => @$input["kel_name"][$key],
                ]);
        }

        Flash::success('Data Kodepos Kecamatan '. @$input["nama"] .' berhasil disimpan');
        return redirect(route('panel.kodepos.index'));
    }

    public function destroy($id)
    {
        $akses = Auth::user();
        if(!$akses->can('kodepos-delete')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        /** Panel Admin Wilayah Akses */
        check_panel_access();

        $wilayah = $this->wilayahRepository->findWithoutFail($akses->id_wilayah);
        if(empty($wilayah)){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        if($akses->id_wilayah != $wilayah->id){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        $kecamatan = $this->kodeposRepository->findWithoutFail($id);

        if (empty($kecamatan)) {
            Flash::error('Kecamatan not found');
            return redirect(route('panel.kodepos.index'));
        }

        //Hapus data kelurahan
        $this->kodeposRepository->where('id_parent', $id)->delete();
        $this->kodeposRepository->delete($id);
        
        //Hapus data kecamatan
        Flash::success('Kecamatan deleted successfully.');
        return redirect(route('panel.kodepos.index'));
    }
}
