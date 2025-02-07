<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests;
use App\DataTables\Panel\PejabatwilayahDataTable;
use App\Http\Requests\CreatePejabatwilayahRequest;
use App\Http\Requests\UpdatePejabatwilayahRequest;

use App\Repositories\PejabatwilayahRepository;
use App\Repositories\JabatanRepository;
use App\Repositories\WilayahRepository;
use Flash;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\UploadFileService;
use Carbon\Carbon;

class PejabatwilayahController extends AppBaseController
{
    /** @var  PejabatwilayahRepository */
    private $pejabatwilayahRepository;
    private $tipe = 'pemda';
    private $jabatan;
    private $wilayah;
    private $uploadFile;
    private $path = 'pemda/';

    public function __construct(PejabatwilayahRepository $pejabatwilayahRepo, JabatanRepository $jabatanRepo, WilayahRepository $wilayahRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:pejabatwilayah-edit', ['only' => ['edit']]);
        $this->middleware('can:pejabatwilayah-store', ['only' => ['store']]);
        $this->middleware('can:pejabatwilayah-show', ['only' => ['show']]);
        $this->middleware('can:pejabatwilayah-update', ['only' => ['update']]);
        $this->middleware('can:pejabatwilayah-delete', ['only' => ['delete']]);
        $this->middleware('can:pejabatwilayah-create', ['only' => ['create']]);
        $this->pejabatwilayahRepository = $pejabatwilayahRepo;
        $this->jabatan = $jabatanRepo;
        $this->wilayah = $wilayahRepo;
        $this->uploadFile = new UploadFileService();
    }

    /**
     * Display a listing of the Pejabatwilayah.
     *
     * @param PejabatwilayahDataTable $pejabatwilayahDataTable
     * @return Response
     */
    public function index(PejabatwilayahDataTable $pejabatwilayahDataTable)
    {
        $akses = Auth::user();
        if(!$akses->can('pejabatwilayah-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        /** Panel Admin Wilayah Akses */
        check_panel_access();

        $wilayah = $this->wilayah->findWithoutFail($akses->id_wilayah);
        if(empty($wilayah)){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        $data = [
            'wilayah' => $wilayah
        ];

        return $pejabatwilayahDataTable->setWilayah($wilayah->id)->renders('admin_panel.wilayahs.index_pejabat',[],[],$data);
    }

    /**
     * Show the form for creating a new Pejabatwilayah.
     *
     * @return Response
     */
    public function create()
    {
        $akses = Auth::user();
        if(!$akses->can('pejabatwilayah-create')){
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

        return view('admin_panel.wilayahs.create_pejabat')
            ->with('jabatan', $jabatan)
            ->with('wilayah', $wilayah);
    }

    /**
     * Store a newly created Pejabatwilayah in storage.
     *
     * @param CreatePejabatwilayahRequest $request
     *
     * @return Response
     */
    public function store(CreatePejabatwilayahRequest $request)
    {
        $akses = Auth::user();
        if(!$akses->can('pejabatwilayah-store')){
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

        if (@$request->foto) {
            // $input['foto'] = $this->saveFile->setImage(@$request->foto)->setStorage('pemda')->handle();
            $input['foto'] = $this->uploadFile->uploadFile($this->path, 'pejabat', @$request->foto);
        } else {
            $input['foto'] = null;
        }
        
        foreach ($input['tahun'] as $key => $val) {
            // cek apa data sudah ada, kalau sudah ada di update datanya, kalau belom ada di insert baru
            $cek = $this->pejabatwilayahRepository->where('id_jabatan', $input["id_jabatan"])->where('id_wilayah', $wilayah->id)->where('tahun', $val)->first();
            if(empty($cek)){
                $pejabatdaerah = $this->pejabatwilayahRepository->create([
                    "id_wilayah" =>  $wilayah->id,
                    "id_jabatan" => $input["id_jabatan"],
                    "nama_lengkap" => $input["nama_lengkap"],
                    "tahun" => $val,
                    "foto" => $input["foto"]
                ]);
            }
            else{
                $pejabatdaerah = $this->pejabatwilayahRepository->update([
                    "nama_lengkap" => $input["nama_lengkap"],
                    "foto" => $input["foto"]
                ], $cek->id);
            }            
        }

        Flash::success('Pemerintahan Daerah berhasil disimpan.');
        return redirect(route('panel.pejabat_wilayahs.index'));
    }

    public function edit($id){
        $akses = Auth::user();

        if(!$akses->can('pejabatwilayah-edit')){
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

        $pejabat = $this->pejabatwilayahRepository->findWithoutFail($id);

        if (empty($pejabat)) {
            Flash::error('Pemerintahan Daerah tidak ditemukan');
            return redirect(route('panel.pejabat_wilayahs.index'));
        }        
        
        if ($pejabat->id_wilayah != $wilayah->id) {
            Flash::error('Pemerintahan Daerah tidak ditemukan pada '. $wilayah->nama);
            return redirect(route('panel.pejabat_wilayahs.index'));
        }

        $jabatan = $this->jabatan->getArrayJabatan($this->tipe, @$wilayah->tipe);

        return view('admin_panel.wilayahs.edit_pejabat')
            ->with('jabatan', $jabatan)
            ->with('wilayah', $wilayah)
            ->with('pejabat', $pejabat);
    }

    public function update($id, UpdatePejabatwilayahRequest $request)
    {
        $akses = Auth::user();
        if(!$akses->can('pejabatwilayah-update')){
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

        $pejabat = $this->pejabatwilayahRepository->findWithoutFail($id);

        if (empty($pejabat)) {
            Flash::error('Pemerintahan Daerah tidak ditemukan');
            return redirect(route('panel.pejabat_wilayahs.index'));
        }        
        
        if ($pejabat->id_wilayah != $wilayah->id) {
            Flash::error('Pemerintahan Daerah tidak ditemukan pada '. $wilayah->nama);
            return redirect(route('panel.pejabat_wilayahs.index'));
        }

        $input = $request->all();
        $input['updated_by'] = $akses->id;
        $input['history_updated'] = savingHistory($akses, $pejabat->history_updated);

        if (strtotime(@$input["tahun_lantik"]) > strtotime(@$input["tahun_akhir"])) {
            return redirect()->back()->withInput($input)->withErrors(['Periode Awal tidak boleh lebih besar dari periode akhir']);
        }
        
        if (@$request->foto) {
            // $input['foto'] = $this->saveFile->setModel(@$pejabatwilayah->foto)->setImage(@$request->foto)->setStorage('pemda')->isDelete(1)->handle();
            $input['foto'] = $this->uploadFile->uploadFile($this->path, 'foro', @$request->foto, $pejabat, 'update');
        } else {
            $input['foto'] = @$pejabat->foto;
        }

        $pejabat = $this->pejabatwilayahRepository->update($input, $id);

        Flash::success('Pemerintahan Daerah berhasil di simpan.');
        return redirect(route('panel.pejabat_wilayahs.index'));
    }

    /**
     * Remove the specified Pejabatwilayah from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $akses = Auth::user();
        if(!$akses->can('pejabatwilayah-delete')){
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
        
        $pejabat = $this->pejabatwilayahRepository->findWithoutFail($id);
        
        if (empty($pejabat)) {
            Flash::error('Pejabat Pemerintah Daerah tidak ditemukan');
            return redirect(route('panel.pejabat_wilayahs.index'));
        }

        $updated = [
            'history_updated' => savingHistory($akses, $pejabat->history_updated, true)
        ];

        $this->pejabatwilayahRepository->update($updated, $id);
        $this->pejabatwilayahRepository->delete($id);

        Flash::success('Pejabat Pemerintah Daerah berhasil dihapus.');
        return redirect(route('panel.pejabat_wilayahs.index'));
    }
}
