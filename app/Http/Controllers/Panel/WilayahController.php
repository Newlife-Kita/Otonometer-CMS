<?php

namespace App\Http\Controllers\Panel;

use App\Repositories\WilayahRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\DataranRepository;
use Response;
use Illuminate\Support\Facades\Auth;
use App\Services\UploadFileService;
use Illuminate\Http\Request;

class WilayahController extends AppBaseController
{
    /** @var  WilayahRepository */
    private $wilayahRepository;
    private $dataranRepository;

    public function __construct(WilayahRepository $wilayahRepo, DataranRepository $dataranRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:wilayah-edit', ['only' => ['edit']]);
        $this->middleware('can:wilayah-show', ['only' => ['show']]);
        $this->middleware('can:wilayah-update', ['only' => ['update']]);
        $this->wilayahRepository = $wilayahRepo;
        $this->dataranRepository = $dataranRepo;
    }

    /**
     * Display a listing of available Provinces.
     *
     * @param WilayahDataTable $wilayahDataTable
     * @return Response
     */
    public function index()
    {
        $akses = Auth::user();
        if(!$akses->can('wilayah-show')){
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

        $dataran = $this->dataranRepository->all()->pluck('nama','id');
        return view('admin_panel.wilayahs.index')->with('wilayah', $wilayah)->with('dataran', $dataran); 
    }

    public function update(Request $request){

        $akses = Auth::user();
        if(!$akses->can('wilayah-update')){
            return response()->json('THIS PAGE IS UNAUTHORIZED.', 403);
        }
        /** Panel Admin Wilayah Akses */
        check_panel_access();

        $wilayah = $this->wilayahRepository->findWithoutFail($akses->id_wilayah);
        if(empty($wilayah)){
            return response()->json('THIS PAGE IS UNAUTHORIZED.', 403);
        }
        
        if($akses->id_wilayah != $wilayah->id){
            return response()->json('THIS PAGE IS UNAUTHORIZED.', 403);
        }

        $input = $request->all();
        $this->wilayahRepository->update([
            'alamat_kantor_pemerintahan' => @$input["alamat_kantor_pemerintahan"],
            'alamat_kantor_dprd' => @$input["alamat_kantor_dprd"],
            'id_dataran' => @$input["id_dataran"],
            'latitude' => @$input["latitude"],
            'longitude' => @$input["longitude"],
            'nama' => @$input["nama"],
        ],$akses->id_wilayah);

        return response()->json(['valid' => true, 'message' => 'Data '. $wilayah->nama .' berhasil di ubah!']);
    }
}
