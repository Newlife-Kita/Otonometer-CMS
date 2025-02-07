<?php

namespace App\Http\Controllers\Panel;

use App\DataTables\Panel\DatawilayahDataTable;
use App\Repositories\WilayahRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Models\Ekonomi;
use App\Models\Nomenklaturtahun;
use App\Repositories\DataranRepository;
use App\Repositories\DatawilayahRepository;
use App\Repositories\EkonomiRepository;
use Response;
use Illuminate\Support\Facades\Auth;
use App\Services\UploadFileService;
use Illuminate\Http\Request;

class DatawilayahController extends AppBaseController
{
    /** @var  WilayahRepository */
    private $wilayahRepository;
    private $datawilayahRepository;
    private $pdrb;

    public function __construct(WilayahRepository $wilayahRepo, DatawilayahRepository $datawilayahRepo, EkonomiRepository $ekonomiRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:datawilayah-create', ['only' => ['edit']]);
        $this->middleware('can:datawilayah-edit', ['only' => ['edit']]);
        $this->middleware('can:datawilayah-show', ['only' => ['show']]);
        $this->middleware('can:datawilayah-store', ['only' => ['store']]);
        $this->middleware('can:datawilayah-update', ['only' => ['update']]);
        $this->wilayahRepository = $wilayahRepo;
        $this->datawilayahRepository = $datawilayahRepo;
        $this->pdrb = $ekonomiRepo;
    }

    /**
     * Display a listing of available Provinces.
     *
     * @param WilayahDataTable $wilayahDataTable
     * @return Response
     */
    public function index(DatawilayahDataTable $dataTable)
    {
        $akses = Auth::user();
        if(!$akses->can('datawilayah-show')){
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

        $lastInfo = $this->datawilayahRepository->where('id_wilayah', $wilayah->id)->orderBy('tahun', 'desc')->first();
        $data = [
            'tahun' => Nomenklaturtahun::orderBy('tahun','desc')->get()->pluck('tahun','tahun'),
            'lastInfo' => $lastInfo,
            'wilayah' => $wilayah,
            'pdrb' => Ekonomi::all()->pluck('nama','id'),
        ];
        return $dataTable->setWilayah($akses->id_wilayah)->renders('admin_panel.wilayahs.index_pdrb',[],[],$data);
    }

    public function submit(Request $request){
        $akses = Auth::user();
        if(!$akses->canAny(['datawilayah-store', 'datawilayah-update'])){
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

        $id_sektor = $request->id_sektor;
        $nilai_sektor = $request->nilai_sektor;
        $ketinggian = intval($request->ketinggian) > 0 ? $request->ketinggian : null;
        $luas_wilayah = $request->luas_wilayah;
        $jumlah_penduduk = $request->jumlah_penduduk;
        $id = $request->nilai_id;

        /** Cek info value */
        $sql = $this->datawilayahRepository->where('id_wilayah', $wilayah->id)->where('tahun', @$request->tahun);
        if(intval(@$id) > 0) $sql->where('id', '!=', $id);

        $cek = $sql->first();

        if(empty($cek)){

            if(intval(@$id) > 0){
                $this->datawilayahRepository->update([
                    'id_sektor' =>	$id_sektor,
                    'nilai_sektor' => $nilai_sektor,
                    'ketinggian' =>	$ketinggian,
                    'luas_wilayah' => $luas_wilayah,
                    'jumlah_penduduk' => $jumlah_penduduk
                    
                ], @$request->nilai_id);
            }
            else{            
                $this->datawilayahRepository->create([
                    'id_wilayah' => $wilayah,
                    'id_sektor' =>	$id_sektor,
                    'nilai_sektor' => $nilai_sektor,
                    'ketinggian' =>	$ketinggian,
                    'luas_wilayah' => $luas_wilayah,
                    'jumlah_penduduk' => $jumlah_penduduk
                ], @$request->nilai_id);    
            }
    
            return response()->json(['valid' => true, 'message' => 'Info '. $wilayah->nama .' Tahun '. $request->tahun .' Berhasil disimpan']);    
        }
        else{
            return response()->json(['valid' => false, 'message' => 'Info '. $wilayah->nama .' Tahun '. $request->tahun .' Sudah ada']);
        }
    }
}
