<?php

namespace App\Http\Controllers\Panel;

use App\DataTables\Panel\DprdDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateDprdRequest;
use App\Http\Requests\UpdateDprdRequest;
use App\Repositories\DprdRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\JabatanRepository;
use App\Repositories\KomisiRepository;
use App\Repositories\PartaiRepository;
use App\Repositories\WilayahRepository;

use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\UploadFileService;
use Carbon\Carbon;

class DprdController extends AppBaseController
{
    /** @var  DprdRepository */
    private $dprdRepository;
    private $tipe = 'dprd';
    private $jabatan;
    private $wilayah;
    private $komisi;
    private $parpol;
    private $uploadFile;
    private $path = 'dprd/';

    public function __construct(DprdRepository $dprdRepo, JabatanRepository $jabatRepo, WilayahRepository $wilayahRepo, PartaiRepository $partaiRepo, KomisiRepository $komisiRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:dprd-edit', ['only' => ['edit']]);
        $this->middleware('can:dprd-store', ['only' => ['store']]);
        $this->middleware('can:dprd-show', ['only' => ['show']]);
        $this->middleware('can:dprd-update', ['only' => ['update']]);
        $this->middleware('can:dprd-delete', ['only' => ['delete']]);
        $this->middleware('can:dprd-create', ['only' => ['create']]);
        $this->dprdRepository = $dprdRepo;
        $this->jabatan = $jabatRepo;
        $this->wilayah = $wilayahRepo;
        $this->komisi = $komisiRepo;
        $this->parpol = $partaiRepo;
        $this->uploadFile = new UploadFileService();
    }

    /**
     * Display a listing of the Dprd.
     *
     * @param DprdDataTable $dprdDataTable
     * @return Response
     */
    public function index(DprdDataTable $dprdDataTable)
    {
        $akses = Auth::user();
        if (!$akses->can('dprd-show')) {
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
        
        $jabatan = $this->jabatan->getArrayJabatan($this->tipe, @$wilayah->tipe, true);
        $parpol = $this->parpol->getArrayPartai();
        $komisi = $this->komisi->getArrayKomisi();

        $data = [
            'wilayah' => $wilayah,
            'parpol' => $parpol,
            'komisi' => $komisi,
            'jabatan' => $jabatan,
        ];

        return $dprdDataTable->setWilayah($wilayah->id)->setJabatan($jabatan)->setHalaman('anggota')->renders('admin_panel.dprd.index',[],[],$data);
    }

    /**
     * Show the form for creating a new Dprd.
     *
     * @return Response
     */
    public function create()
    {
        $akses = Auth::user();
        if (!$akses->can('dprd-create')) {
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

        $jabatan = $this->jabatan->getArrayJabatan($this->tipe, @$wilayah->tipe, true);
        $parpol = $this->parpol->getArrayPartai();
        $komisi = $this->komisi->getArrayKomisi();

        
        return view('admin_panel.dprd.create')
            ->with('komisi', $komisi)
            ->with('partai', $parpol)
            ->with('jabatan', $jabatan)
            ->with('wilayah', $wilayah)
            ->with('page', 'anggota')
            ->with('routes_action', 'panel.anggota_dprds.store')
            ->with('routes_cancel', 'panel.anggota_dprds.index');
    }

    /**
     * Store a newly created Dprd in storage.
     *
     * @param CreateDprdRequest $request
     *
     * @return Response
     */
    public function store(CreateDprdRequest $request)
    {
        $akses = Auth::user();
        if (!$akses->can('dprd-store')) {
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
            
        $input = $request->all();
        $input['created_by'] = $akses->id;
        $input['history_updated'] = savingHistory($akses);

        if (strtotime(@$input["tahun_lantik"]) > strtotime(@$input["tahun_akhir"])) {
            return redirect()->back()->withInput($input)->withErrors(['Periode Awal tidak boleh lebih besar dari periode akhir']);
        }

        $jabatan = $this->jabatan->findWithoutFail(@$input["id_jabatan"]);
        
        if(empty($jabatan)){
            return redirect()->back()->withInput($input)->withErrors(['Jabatan tidak ditemukan']);
        }

        // validasi data per tahun, kalau data sudah tersedia balikin message
        $validasi = null;
        $val_tahun = '';
        foreach ($input['tahun'] as $key => $val) {
            $cek = $this->dprdRepository->where('id_jabatan', $input["id_jabatan"])->where('id_wilayah', $wilayah->id)->where('tahun', $val)->count();
            if(intval(@$cek) >= $jabatan->total_person){
                $validasi = true;
                $val_tahun .= $val . ', ';
            }
        }

        if(!empty($validasi)){ 
            return redirect()->back()->withInput($input)->withErrors(['Jabatan '. $jabatan->nama .' Tahun Menjabat [' . rtrim($val_tahun, ', ') . '] sudah ada']);
        }

        if (@$request->foto) {
            // $input['foto'] = $this->saveFile->setImage(@$request->foto)->setStorage('dprd')->isDelete(1)->handle();
            $input['foto'] = $this->uploadFile->uploadFile($this->path, 'foto', @$request->foto);
        } 
        else {
            $input['foto'] = null;
        }

        foreach ($input['tahun'] as $key => $val) {
            if($jabatan->total_person  == '-1'){
                    $pejabatdaerah = $this->dprdRepository->create([
                            "id_wilayah" =>  $wilayah->id,
                            "id_jabatan" => $input["id_jabatan"],
                            "id_partai" => $input["id_partai"],
                            "id_komisi" => @$input["id_komisi"],
                            "nama_lengkap" => $input["nama_lengkap"],
                            "tahun" => $val,
                            "tahun_lantik" => @$input["tahun_lantik"],	
                            "tahun_akhir" => @$input["tahun_akhir"],
                            "foto" => $input["foto"],
                            "created_by" => $input["created_by"],
                            "history_updated" => $input["history_updated"],
                        ]);
                    }
                    else{
                        // cek apa data sudah ada, kalau sudah ada di update datanya, kalau belom ada di insert baru
                        $cek = $this->dprdRepository->where('id_jabatan', $input["id_jabatan"])->where('id_wilayah', $wilayah->id)->where('tahun', $val)->get();
                        if($cek->count() < $jabatan->total_person){
                            $pejabatdaerah = $this->dprdRepository->create([
                                "id_wilayah" =>  $wilayah->id,
                                "id_jabatan" => $input["id_jabatan"],
                                "id_partai" => $input["id_partai"],
                                "id_komisi" => @$input["id_komisi"],
                                "nama_lengkap" => $input["nama_lengkap"],
                                "tahun" => $val,
                                "tahun_lantik" => @$input["tahun_lantik"],	
                                "tahun_akhir" => @$input["tahun_akhir"],
                                "foto" => $input["foto"],
                                "created_by" => $input["created_by"],
                                "history_updated" => $input["history_updated"],
                            ]);
                        }
                        else{
                            if($jabatan->total_person == '1'){                        
                                $pejabatdaerah = $this->dprdRepository->where('id_jabatan', $input["id_jabatan"])->where('id_wilayah', $wilayah->id)->where('tahun', $val)->update([
                                    "id_jabatan" => $input["id_jabatan"],
                                    "id_partai" => $input["id_partai"],
                                    "id_komisi" => @$input["id_komisi"],
                                    "nama_lengkap" => $input["nama_lengkap"],
                                    "tahun_lantik" => @$input["tahun_lantik"],	
                                    "tahun_akhir" => @$input["tahun_akhir"],
                                    "foto" => $input["foto"],
                                    "created_by" => $input["created_by"],
                                    "history_updated" => $input["history_updated"],
                                ]);
                            }
                        }   
                    }         
            }

        Flash::success('Anggota DPRD berhasil disimpan.');
        return redirect(route('panel.anggota_dprds.index'));
    }

    public function edit($id){
        
        $akses = Auth::user();
        if (!$akses->can('dprd-edit')) {
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

        $dprd = $this->dprdRepository->findWithoutFail($id);

        if(empty($dprd)){
            Flash::error('Anggota DPRD tidak ditemukan.');
            return redirect(route('panel.anggota_dprds.index'));
        }

        if($dprd->id_wilayah != $wilayah->id){
            Flash::error('Anggota DPRD tidak ditemukan pada ' . $wilayah->nama);
            return redirect(route('panel.anggota_dprds.index'));
        }

        $jabatan = $this->jabatan->getArrayJabatan($this->tipe, @$wilayah->tipe, true);
        $parpol = $this->parpol->getArrayPartai();
        $komisi = $this->komisi->getArrayKomisi();

        
        return view('admin_panel.dprd.edit')
            ->with('komisi', $komisi)
            ->with('partai', $parpol)
            ->with('jabatan', $jabatan)
            ->with('wilayah', $wilayah)
            ->with('dprd', $dprd)
            ->with('page', 'anggota')
            ->with('routes_action', 'panel.anggota_dprds.update')
            ->with('routes_cancel', 'panel.anggota_dprds.index');
    }

    public function update($id, Request $request)
    {
        $akses = Auth::user();
        if (!$akses->can('dprd-update')) {
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

        $dprd = $this->dprdRepository->findWithoutFail($id);

        if(empty($dprd)){
            Flash::error('Anggota DPRD tidak ditemukan.');
            return redirect(route('panel.anggota_dprds.index'));
        }

        if($dprd->id_wilayah != $wilayah->id){
            Flash::error('Anggota DPRD tidak ditemukan pada ' . $wilayah->nama);
            return redirect(route('panel.anggota_dprds.index'));
        }

        $input = $request->all();
        $input['updated_by'] = $akses->id;
        $input['history_updated'] = savingHistory($akses, $dprd->history_updated);

        if (strtotime(@$input["tahun_lantik"]) > strtotime(@$input["tahun_akhir"])) {
            return redirect()->back()->withInput($input)->withErrors(['Periode Awal tidak boleh lebih besar dari periode akhir']);
        }
        
        $jabatan = $this->jabatan->findWithoutFail(@$input["id_jabatan"]);
        
        if(empty($jabatan)){
            return redirect()->back()->withInput($input)->withErrors(['Jabatan tidak ditemukan']);
        }

        // cek apa data sudah ada, dan total data sesuai dengan jumlah orang di master
        if($jabatan->total_person != "-1"){

            $cek = $this->dprdRepository->where('id_jabatan', $input["id_jabatan"])->where('id_wilayah', $wilayah->id)->where('tahun', @$input["tahun"])->where('id', '!=', $id)->count();
            if($cek >= $jabatan->total_person){
                return redirect()->back()->withInput($input)->withErrors(['Anggota DPRD Tahun jabatan ['. @$input["tahun"] .'] sudah ada!']);
            }
        }

        if (@$request->foto) {
            // $input['foto'] = $this->saveFile->setImage(@$request->foto)->setStorage('pemda')->handle();
            $input['foto'] = $this->uploadFile->uploadFile($this->path, 'foto', @$request->foto, $dprd, 'update');
        } else {
            $input['foto'] = @$dprd->foto;
        }
        
        $dprd = $this->dprdRepository->update([
            "id_partai" => $input["id_partai"],
            "id_jabatan" => $input["id_jabatan"],
            "nama_lengkap" => $input["nama_lengkap"],
            "tahun_lantik" => @$input["tahun_lantik"],	
            "tahun_akhir" => @$input["tahun_akhir"],
            "tahun" => @$input["tahun"],
            "foto" => $input["foto"],
            "updated_by" => $input["updated_by"],
            "history_updated" => $input["history_updated"],
        ], $id);

        Flash::success('Anggota DPRD berhasil disimpan.');
        return redirect(route('panel.anggota_dprds.index'));
    }

    public function destroy($id)
    {
        $akses = Auth::user();
        if(!$akses->can('dprd-delete')){
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

        $dprd = $this->dprdRepository->findWithoutFail($id);
        
        if(empty($dprd)){
            Flash::error('Anggota DPRD tidak ditemukan.');
            return redirect(route('panel.anggota_dprds.index'));
        }

        if($dprd->id_wilayah != $wilayah->id){
            Flash::error('Anggota DPRD tidak ditemukan pada ' . $wilayah->nama);
            return redirect(route('panel.anggota_dprds.index'));
        }

        $updated = [
            'history_updated' => savingHistory($akses, $dprd->history_updated, true)
        ];

        $this->dprdRepository->update($updated, $id);
        $this->dprdRepository->delete($id);

        Flash::success('Anggota DPRD berhasil dihapus.');
        return redirect(route('panel.anggota_dprds.index'));
    }

    /**
     * Display a listing of the Dprd.
     *
     * @param DprdDataTable $dprdDataTable
     * @return Response
     */
    public function index_pimpinan(DprdDataTable $dprdDataTable)
    {
        $akses = Auth::user();
        if (!$akses->can('dprd-show')) {
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

        $jabatan = $this->jabatan->getArrayJabatan($this->tipe, @$wilayah->tipe);
        $parpol = $this->parpol->getArrayPartai();
        $komisi = $this->komisi->getArrayKomisi();

        $data = [
            'wilayah' => $wilayah,
            'parpol' => $parpol,
            'komisi' => $komisi,
            'jabatan' => $jabatan,
        ];

        return $dprdDataTable->setWilayah($wilayah->id)->setJabatan($jabatan)->setHalaman('pimpinan')->renders('admin_panel.dprd.index_pimpinan',[],[],$data);
    }

    /**
     * Show the form for creating a new Dprd.
     *
     * @return Response
     */
    public function create_pimpinan()
    {
        $akses = Auth::user();
        if (!$akses->can('dprd-create')) {
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

        $jabatan = $this->jabatan->getArrayJabatan($this->tipe, @$wilayah->tipe);
        $parpol = $this->parpol->getArrayPartai();
        $komisi = $this->komisi->getArrayKomisi();

        return view('admin_panel.dprd.create')
            ->with('komisi', $komisi)
            ->with('partai', $parpol)
            ->with('jabatan', $jabatan)
            ->with('wilayah', $wilayah)
            ->with('page', 'pimpinan')
            ->with('routes_action', 'panel.pimpinan_dprds.store')
            ->with('routes_cancel', 'panel.pimpinan_dprds.index');
    }
    
    /**
     * action for store a new Dprd.
     *
     * @return Response
     */
    public function store_pimpinan(CreateDprdRequest $request)
    {
        $akses = Auth::user();
        if (!$akses->can('dprd-store')) {
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
            
        $input = $request->all();
        $input['created_by'] = $akses->id;
        $input['history_updated'] = savingHistory($akses);

        if (strtotime(@$input["tahun_lantik"]) > strtotime(@$input["tahun_akhir"])) {
            return redirect()->back()->withInput($input)->withErrors(['Periode Awal tidak boleh lebih besar dari periode akhir']);
        }

        $jabatan = $this->jabatan->findWithoutFail(@$input["id_jabatan"]);
        
        if(empty($jabatan)){
            return redirect()->back()->withInput($input)->withErrors(['Jabatan tidak ditemukan']);
        }

        // validasi data per tahun, kalau data sudah tersedia balikin message
        $validasi = null;
        $val_tahun = '';
        foreach ($input['tahun'] as $key => $val) {
            $cek = $this->dprdRepository->where('id_jabatan', $input["id_jabatan"])->where('id_wilayah', $wilayah->id)->where('tahun', $val)->count();
            if(intval(@$cek) >= $jabatan->total_person){
                $validasi = true;
                $val_tahun .= $val . ', ';
            }
        }

        if(!empty($validasi)){ 
            return redirect()->back()->withInput($input)->withErrors(['Jabatan '. $jabatan->nama .' Tahun Menjabat [' . rtrim($val_tahun, ', ') . '] sudah ada']);
        }

        if (@$request->foto) {
            // $input['foto'] = $this->saveFile->setImage(@$request->foto)->setStorage('dprd')->isDelete(1)->handle();
            $input['foto'] = $this->uploadFile->uploadFile($this->path, 'foto', @$request->foto);
        } 
        else {
            $input['foto'] = null;
        }

        foreach ($input['tahun'] as $key => $val) {
            if($jabatan->total_person  == '-1'){
                    $pejabatdaerah = $this->dprdRepository->create([
                            "id_wilayah" =>  $wilayah->id,
                            "id_jabatan" => $input["id_jabatan"],
                            "id_partai" => $input["id_partai"],
                            "nama_lengkap" => $input["nama_lengkap"],
                            "tahun" => $val,
                            "tahun_lantik" => @$input["tahun_lantik"],	
                            "tahun_akhir" => @$input["tahun_akhir"],
                            "foto" => $input["foto"],
                            "created_by" => $input["created_by"],
                            "history_updated" => $input["history_updated"],
                        ]);
                    }
                    else{
                        // cek apa data sudah ada, kalau sudah ada di update datanya, kalau belom ada di insert baru
                        $cek = $this->dprdRepository->where('id_jabatan', $input["id_jabatan"])->where('id_wilayah', $wilayah->id)->where('tahun', $val)->get();
                        if($cek->count() < $jabatan->total_person){
                            $pejabatdaerah = $this->dprdRepository->create([
                                "id_wilayah" =>  $wilayah->id,
                                "id_jabatan" => $input["id_jabatan"],
                                "id_partai" => $input["id_partai"],
                                "nama_lengkap" => $input["nama_lengkap"],
                                "tahun" => $val,
                                "tahun_lantik" => @$input["tahun_lantik"],	
                                "tahun_akhir" => @$input["tahun_akhir"],
                                "foto" => $input["foto"],
                                "created_by" => $input["created_by"],
                                "history_updated" => $input["history_updated"],
                            ]);
                        }
                        else{
                            if($jabatan->total_person == '1'){                        
                                $pejabatdaerah = $this->dprdRepository->where('id_jabatan', $input["id_jabatan"])->where('id_wilayah', $wilayah->id)->where('tahun', $val)->update([
                                    "id_partai" => $input["id_partai"],
                                    "nama_lengkap" => $input["nama_lengkap"],
                                    "tahun_lantik" => @$input["tahun_lantik"],	
                                    "tahun_akhir" => @$input["tahun_akhir"],
                                    "foto" => $input["foto"],
                                    "created_by" => $input["created_by"],
                                    "history_updated" => $input["history_updated"],
                                ]);
                            }
                        }   
                    }         
            }

        Flash::success('Pimpinan DPRD berhasil disimpan.');
        return redirect(route('panel.pimpinan_dprds.index'));
    }
    
    /**
     * form edit dprd using parameter id
     *
     * @return Response
    */
    public function edit_pimpinan($id){
        $akses = Auth::user();
        if (!$akses->can('dprd-edit')) {
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

        $dprd = $this->dprdRepository->findWithoutFail($id);

        if(empty($dprd)){
            Flash::error('Pimpinan DPRD tidak ditemukan.');
            return redirect(route('panel.pimpinan_dprds.index'));
        }

        if($dprd->id_wilayah != $wilayah->id){
            Flash::error('Pimpinan DPRD tidak ditemukan pada ' . $wilayah->nama);
            return redirect(route('panel.pimpinan_dprds.index'));
        }

        $jabatan = $this->jabatan->getArrayJabatan($this->tipe, @$wilayah->tipe);
        $parpol = $this->parpol->getArrayPartai();
        $komisi = $this->komisi->getArrayKomisi();

        return view('admin_panel.dprd.edit')
            ->with('komisi', $komisi)
            ->with('partai', $parpol)
            ->with('jabatan', $jabatan)
            ->with('wilayah', $wilayah)
            ->with('dprd', $dprd)
            ->with('page', 'pimpinan')
            ->with('routes_action', 'panel.pimpinan_dprds.update')
            ->with('routes_cancel', 'panel.pimpinan_dprds.index');
    }

    public function update_pimpinan($id, Request $request)
    {
        $akses = Auth::user();
        if (!$akses->can('dprd-update')) {
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

        $dprd = $this->dprdRepository->findWithoutFail($id);

        if(empty($dprd)){
            Flash::error('Pimpinan DPRD tidak ditemukan.');
            return redirect(route('panel.pimpinan_dprds.index'));
        }

        if($dprd->id_wilayah != $wilayah->id){
            Flash::error('Pimpinan DPRD tidak ditemukan pada ' . $wilayah->nama);
            return redirect(route('panel.pimpinan_dprds.index'));
        }

        $input = $request->all();
        $input['updated_by'] = $akses->id;
        $input['history_updated'] = savingHistory($akses, $dprd->history_updated);

        if (strtotime(@$input["tahun_lantik"]) > strtotime(@$input["tahun_akhir"])) {
            return redirect()->back()->withInput($input)->withErrors(['Periode Awal tidak boleh lebih besar dari periode akhir']);
        }
        
        $jabatan = $this->jabatan->findWithoutFail(@$input["id_jabatan"]);
        
        if(empty($jabatan)){
            return redirect()->back()->withInput($input)->withErrors(['Jabatan tidak ditemukan']);
        }

        // cek apa data sudah ada, dan total data sesuai dengan jumlah orang di master
        if($jabatan->total_person != "-1"){

            $cek = $this->dprdRepository->where('id_jabatan', $input["id_jabatan"])->where('id_wilayah', $wilayah->id)->where('tahun', @$input["tahun"])->where('id', '!=', $id)->count();
            if($cek >= $jabatan->total_person){
                return redirect()->back()->withInput($input)->withErrors(['Pimpinan DPRD Tahun jabatan ['. @$input["tahun"] .'] sudah ada!']);
            }
        }

        if (@$request->foto) {
            // $input['foto'] = $this->saveFile->setImage(@$request->foto)->setStorage('pemda')->handle();
            $input['foto'] = $this->uploadFile->uploadFile($this->path, 'foto', @$request->foto, $dprd, 'update');
        } else {
            $input['foto'] = @$dprd->foto;
        }
        
        $dprd = $this->dprdRepository->update([
            "id_partai" => $input["id_partai"],
            "id_jabatan" => $input["id_jabatan"],
            "nama_lengkap" => $input["nama_lengkap"],
            "tahun_lantik" => @$input["tahun_lantik"],	
            "tahun_akhir" => @$input["tahun_akhir"],
            "tahun" => @$input["tahun"],
            "foto" => $input["foto"],
            "updated_by" => $input["updated_by"],
            "history_updated" => $input["history_updated"],
        ], $id);

        Flash::success('Pimpinan DPRD berhasil disimpan.');
        return redirect(route('panel.pimpinan_dprds.index'));
    }

    public function destroy_pimpinan($id)
    {
        $akses = Auth::user();
        if(!$akses->can('dprd-delete')){
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

        $dprd = $this->dprdRepository->findWithoutFail($id);
        
        if(empty($dprd)){
            Flash::error('Pimpinan DPRD tidak ditemukan.');
            return redirect(route('panel.pimpinan_dprds.index'));
        }

        if($dprd->id_wilayah != $wilayah->id){
            Flash::error('Pimpinan DPRD tidak ditemukan pada ' . $wilayah->nama);
            return redirect(route('panel.pimpinan_dprds.index'));
        }

        $updated = [
            'history_updated' => savingHistory($akses, $dprd->history_updated, true)
        ];

        $this->dprdRepository->update($updated, $id);
        $this->dprdRepository->delete($id);

        Flash::success('Pimpinan DPRD berhasil dihapus.');
        return redirect(route('panel.pimpinan_dprds.index'));
    }
}
