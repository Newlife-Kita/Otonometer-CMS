<?php

namespace App\Http\Controllers\Panel;

use App\DataTables\Panel\TahunDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateBidangnilaiRequest;
use App\Http\Requests\UpdateBidangnilaiRequest;
use App\Repositories\BidangnilaiRepository;
use App\Repositories\BidangRepository;
use App\Repositories\WilayahRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Models\Bidangcatatan;
use App\Models\Bidangekonomi;
use App\Models\Bidangkeuangan;
use App\Models\Bidangstatistik;
use App\Repositories\NomenklaturtahunRepository;
use Carbon\Carbon;
use Exception;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class BidangnilaiController extends AppBaseController
{
    /** @var  BidangnilaiRepository */
    private $wilayah;
    private $bidangRepository;
    private $tahunRepository;
    private $id_keuangan = 1;
    private $id_ekonomi = 2;
    private $id_statistik = 3;

    public function __construct(BidangRepository $bidangRepo, WilayahRepository $wilayahrepo, NomenklaturtahunRepository $tahunRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:bidangnilai-edit', ['only' => ['edit']]);
        $this->middleware('can:bidangnilai-store', ['only' => ['store']]);
        $this->middleware('can:bidangnilai-show', ['only' => ['show']]);
        $this->middleware('can:bidangnilai-update', ['only' => ['update']]);
        $this->middleware('can:bidangnilai-delete', ['only' => ['delete']]);
        $this->middleware('can:bidangnilai-create', ['only' => ['create']]);
        $this->wilayah = $wilayahrepo;
        $this->bidangRepository = $bidangRepo;
        $this->tahunRepository = $tahunRepo;
    }

    public function index_keuangan(TahunDataTable $dataTable){        
        $akses = Auth::user();
        if (!$akses->can('bidangnilai-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        /** Panel Admin Wilayah Akses */
        check_panel_access();

        $wilayah = $this->wilayah->findWithoutFail($akses->id_wilayah);
        if(empty($wilayah)){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        if($akses->id_wilayah != $wilayah->id){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        return $dataTable->setHalaman('keuangan')->renders('admin_panel.bidang_nilai.index',[],[], ['halaman' => 'Keuangan', 'wilayah' => $wilayah]);        
    }

    public function edit_keuangan($tahun){     
        $akses = Auth::user();
        if (!$akses->can('bidangnilai-edit')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        /** Panel Admin Wilayah Akses */
        check_panel_access();

        $wilayah = $this->wilayah->findWithoutFail($akses->id_wilayah);
        if(empty($wilayah)){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        if($akses->id_wilayah != $wilayah->id){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        
        $tablename = 'nomenklatur_amount_' . @$tahun;
        if (Schema::hasTable($tablename)) {
        }
        else {
            Flash::error('Tahun data belum tersedia!');
            return redirect(route('panel.data_keuangan.index'));
        }

    $bidangs = $this->bidangRepository->getArrayBidangTable($this->id_keuangan);
        
        $data_bidang = null;
        foreach($bidangs as $bidang){
            $nilai = DB::table($tablename)
            ->selectRaw('id, nilai')
            ->where('id_wilayah', $wilayah->id)
            ->where('id_bidang', @$bidang["id"])
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->first();

            $data_bidang[] = [
                'id' => @$nilai->id,
                'id_bidang' => @$bidang["id"],
                'kode_bidang' => $bidang["code"],
                'nama_bidang' => $bidang["name"],
                'level' => $bidang["level"],
                'data' => $bidang["data"],
                'nilai' => @$nilai->nilai,
            ];
        }
        return view('admin_panel.bidang_nilai.edit')
            ->with('tahun', $tahun)
            ->with('wilayah', $wilayah)
            ->with('data_bidang', $data_bidang)
            ->with('halaman', 'Keuangan')
            ->with('routes_action', 'panel.data_keuangan.update')
            ->with('routes_cancel', 'panel.data_keuangan.index');
    }

    public function update_keuangan(Request $request){
        $akses = Auth::user();
        if (!$akses->can('bidangnilai-update')) {
            return response()->json('THIS PAGE IS UNAUTHORIZED.', 403);
        }
        /** Panel Admin Wilayah Akses */
        check_panel_access();

        $wilayah = $this->wilayah->findWithoutFail($akses->id_wilayah);
        if(empty($wilayah)){
            return response()->json('THIS PAGE IS UNAUTHORIZED.', 403);
        }

        if($akses->id_wilayah != $wilayah->id){
            return response()->json('THIS PAGE IS UNAUTHORIZED.', 403);
        }
        
        $input = $request->all();
        $id = @$input["id"];
        $nilai = @$input["nilai"];
        $tahun = @$input["tahun"];
        
        $tablename = 'nomenklatur_amount_' . $tahun;
        if (Schema::hasTable($tablename)) {

            $data_nilai = DB::table($tablename)->find($id);
            if(empty($data_nilai)){
                return response()->json(['valid' => false, 'message' => 'Data tidak ditemukan!']);
            }
            
            // cek apa sektor ini termasuk sektor keuangan
            // cek apa data ini masuk wilayah yg sama

            if($wilayah->id != $data_nilai->id_wilayah){
                return response()->json(['valid' => false, 'message' => 'Data tidak ditemukan!']);
            }

            $parent = $this->bidangRepository->get_first_sektor($data_nilai->id_bidang);

            if($parent['id'] != $this->id_keuangan){
                return response()->json(['valid' => false, 'message' => 'Invalid data keuangan!']);
            }

            $today = Carbon::now('Asia/Jakarta');
            DB::table($tablename)->whereId($id)->update([
                'nilai' => $nilai,
                'updated_by' => @$akses->id,
                'updated_at' => $today,
                'history_updated' => @$akses->name .' Telah merubah data nilai pada ' . $today->format('d/m/Y H:i:s')
            ]);
            return response()->json(['valid' => true, 'message' => 'Data berhasil disimpan']);
        }
        else {
            return response()->json(['valid' => false, 'message' => 'Tahun data belum tersedia!']);
        }
    }

    public function index_statistik(TahunDataTable $dataTable){
        $akses = Auth::user();
        if (!$akses->can('bidangnilai-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        /** Panel Admin Wilayah Akses */
        check_panel_access();

        $wilayah = $this->wilayah->findWithoutFail($akses->id_wilayah);
        if(empty($wilayah)){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        if($akses->id_wilayah != $wilayah->id){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        return $dataTable->setHalaman('statistik')->renders('admin_panel.bidang_nilai.index',[],[], ['halaman' => 'Statistik', 'wilayah' => $wilayah]);  
    }
    
    public function edit_statistik($tahun){     
        $akses = Auth::user();
        if (!$akses->can('bidangnilai-edit')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        /** Panel Admin Wilayah Akses */
        check_panel_access();

        $wilayah = $this->wilayah->findWithoutFail($akses->id_wilayah);
        if(empty($wilayah)){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        if($akses->id_wilayah != $wilayah->id){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        
        $tablename = 'nomenklatur_amount_' . @$tahun;
        if (Schema::hasTable($tablename)) {
        }
        else {
            Flash::error('Tahun data belum tersedia!');
            return redirect(route('panel.data_statistik.index'));
        }

        $bidangs = $this->bidangRepository->getArrayBidangTable($this->id_statistik);
        
        $data_bidang = null;
        foreach($bidangs as $bidang){
            $nilai = DB::table($tablename)
            ->selectRaw('id, nilai')
            ->where('id_wilayah', $wilayah->id)
            ->where('id_bidang', @$bidang["id"])
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->first();

            $data_bidang[] = [
                'id' => @$nilai->id,
                'id_bidang' => @$bidang["id"],
                'kode_bidang' => $bidang["code"],
                'nama_bidang' => $bidang["name"],
                'level' => $bidang["level"],
                'data' => $bidang["data"],
                'nilai' => @$nilai->nilai,
            ];
        }
        return view('admin_panel.bidang_nilai.edit')
            ->with('tahun', $tahun)
            ->with('wilayah', $wilayah)
            ->with('data_bidang', $data_bidang)
            ->with('halaman', 'Statistik')
            ->with('routes_action', 'panel.data_statistik.update')
            ->with('routes_cancel', 'panel.data_statistik.index');
    }

    public function update_statistik(Request $request){
        $akses = Auth::user();
        if (!$akses->can('bidangnilai-update')) {
            return response()->json('THIS PAGE IS UNAUTHORIZED.', 403);
        }
        /** Panel Admin Wilayah Akses */
        check_panel_access();

        $wilayah = $this->wilayah->findWithoutFail($akses->id_wilayah);
        if(empty($wilayah)){
            return response()->json('THIS PAGE IS UNAUTHORIZED.', 403);
        }

        if($akses->id_wilayah != $wilayah->id){
            return response()->json('THIS PAGE IS UNAUTHORIZED.', 403);
        }
        
        $input = $request->all();
        $id = @$input["id"];
        $nilai = @$input["nilai"];
        $tahun = @$input["tahun"];
        
        $tablename = 'nomenklatur_amount_' . $tahun;
        if (Schema::hasTable($tablename)) {

            $data_nilai = DB::table($tablename)->find($id);
            if(empty($data_nilai)){
                return response()->json(['valid' => false, 'message' => 'Data tidak ditemukan!']);
            }
            
            // cek apa sektor ini termasuk sektor keuangan
            // cek apa data ini masuk wilayah yg sama

            if($wilayah->id != $data_nilai->id_wilayah){
                return response()->json(['valid' => false, 'message' => 'Data tidak ditemukan!']);
            }

            $parent = $this->bidangRepository->get_first_sektor($data_nilai->id_bidang);

            if($parent['id'] != $this->id_statistik){
                return response()->json(['valid' => false, 'message' => 'Invalid data Statistik!']);
            }

            $today = Carbon::now('Asia/Jakarta');
            DB::table($tablename)->whereId($id)->update([
                'nilai' => $nilai,
                'updated_by' => @$akses->id,
                'updated_at' => $today,
                'history_updated' => @$akses->name .' Telah merubah data nilai pada ' . $today->format('d/m/Y H:i:s')
            ]);
            return response()->json(['valid' => true, 'message' => 'Data berhasil disimpan']);
        }
        else {
            return response()->json(['valid' => false, 'message' => 'Tahun data belum tersedia!']);
        }
    }

    public function index_ekonomi(TahunDataTable $dataTable){
        $akses = Auth::user();
        if (!$akses->can('bidangnilai-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        /** Panel Admin Wilayah Akses */
        check_panel_access();

        $wilayah = $this->wilayah->findWithoutFail($akses->id_wilayah);
        if(empty($wilayah)){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        if($akses->id_wilayah != $wilayah->id){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        return $dataTable->setHalaman('ekonomi')->renders('admin_panel.bidang_nilai.index',[],[], ['halaman' => 'Ekonomi', 'wilayah' => $wilayah]);  
    }

    public function edit_ekonomi($tahun){     
        $akses = Auth::user();
        if (!$akses->can('bidangnilai-edit')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        /** Panel Admin Wilayah Akses */
        check_panel_access();

        $wilayah = $this->wilayah->findWithoutFail($akses->id_wilayah);
        if(empty($wilayah)){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        if($akses->id_wilayah != $wilayah->id){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        
        $tablename = 'nomenklatur_amount_' . @$tahun;
        if (Schema::hasTable($tablename)) {
        }
        else {
            Flash::error('Tahun data belum tersedia!');
            return redirect(route('panel.data_ekonomi.index'));
        }

        $bidangs = $this->bidangRepository->getArrayBidangTable($this->id_ekonomi);
        
        $data_bidang = null;
        foreach($bidangs as $bidang){
            $nilai = DB::table($tablename)
            ->selectRaw('id, nilai')
            ->where('id_wilayah', $wilayah->id)
            ->where('id_bidang', @$bidang["id"])
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->first();

            $data_bidang[] = [
                'id' => @$nilai->id,
                'id_bidang' => @$bidang["id"],
                'kode_bidang' => $bidang["code"],
                'nama_bidang' => $bidang["name"],
                'level' => $bidang["level"],
                'data' => $bidang["data"],
                'nilai' => @$nilai->nilai,
            ];
        }
        return view('admin_panel.bidang_nilai.edit')
            ->with('tahun', $tahun)
            ->with('wilayah', $wilayah)
            ->with('data_bidang', $data_bidang)
            ->with('halaman', 'Ekonomi')
            ->with('routes_action', 'panel.data_ekonomi.update')
            ->with('routes_cancel', 'panel.data_ekonomi.index');
    }

    public function update_ekonomi(Request $request){
        $akses = Auth::user();
        if (!$akses->can('bidangnilai-update')) {
            return response()->json('THIS PAGE IS UNAUTHORIZED.', 403);
        }
        /** Panel Admin Wilayah Akses */
        check_panel_access();

        $wilayah = $this->wilayah->findWithoutFail($akses->id_wilayah);
        if(empty($wilayah)){
            return response()->json('THIS PAGE IS UNAUTHORIZED.', 403);
        }
        
        if($akses->id_wilayah != $wilayah->id){
            return response()->json('THIS PAGE IS UNAUTHORIZED.', 403);
        }
        
        $input = $request->all();
        $id = @$input["id"];
        $nilai = @$input["nilai"];
        $tahun = @$input["tahun"];
        
        $tablename = 'nomenklatur_amount_' . $tahun;
        if (Schema::hasTable($tablename)) {

            $data_nilai = DB::table($tablename)->find($id);
            if(empty($data_nilai)){
                return response()->json(['valid' => false, 'message' => 'Data tidak ditemukan!']);
            }
            
            // cek apa sektor ini termasuk sektor keuangan
            // cek apa data ini masuk wilayah yg sama

            if($wilayah->id != $data_nilai->id_wilayah){
                return response()->json(['valid' => false, 'message' => 'Data tidak ditemukan!']);
            }

            $parent = $this->bidangRepository->get_first_sektor($data_nilai->id_bidang);

            if($parent['id'] != $this->id_ekonomi){
                return response()->json(['valid' => false, 'message' => 'Invalid data Ekonomi!']);
            }

            $today = Carbon::now('Asia/Jakarta');
            DB::table($tablename)->whereId($id)->update([
                'nilai' => $nilai,
                'updated_by' => @$akses->id,
                'updated_at' => $today,
                'history_updated' => @$akses->name .' Telah merubah data nilai pada ' . $today->format('d/m/Y H:i:s')
            ]);
            return response()->json(['valid' => true, 'message' => 'Data berhasil disimpan']);
        }
        else {
            return response()->json(['valid' => false, 'message' => 'Tahun data belum tersedia!']);
        }
    }
}