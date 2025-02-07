<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests;
use App\DataTables\Panel\PejabatsudinDataTable;
use App\Http\Requests\CreatePejabatsudinRequest;
use App\Http\Requests\UpdatePejabatsudinRequest;

use App\Repositories\SudinRepository;
use App\Repositories\PejabatsudinRepository;
use App\Repositories\JabatanRepository;
use App\Repositories\WilayahRepository;
use Flash;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\UploadFileService;

class PejabatsudinController extends AppBaseController
{
    /** @var  SudinRepository */
    private $sudinRepository;
    private $jabatan;
    private $wilayah;
    private $pejabatRepo;
    private $bahasaRepository;
    private $uploadFile;
    private $path = 'sudin/';
    private $tipe = 'dinas';

    public function __construct(WilayahRepository $wilayahRepo, SudinRepository $sudinRepo, JabatanRepository $jabatanRepo, PejabatsudinRepository $pejabatsudinRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:pejabatsudin-edit', ['only' => ['edit']]);
        $this->middleware('can:pejabatsudin-store', ['only' => ['store']]);
        $this->middleware('can:pejabatsudin-show', ['only' => ['show']]);
        $this->middleware('can:pejabatsudin-update', ['only' => ['update']]);
        $this->middleware('can:pejabatsudin-delete', ['only' => ['delete']]);
        $this->middleware('can:pejabatsudin-create', ['only' => ['create']]);
        $this->wilayah = $wilayahRepo;
        $this->sudinRepository = $sudinRepo;
        $this->jabatan = $jabatanRepo;
        $this->pejabatRepo = $pejabatsudinRepo;
        $this->uploadFile = new UploadFileService();
    }

    /**
     * Display a listing of the Sudin.
     *
     * @param SudinDataTable $sudinDataTable
     * @return Response
     */
    public function index(PejabatsudinDataTable $dataTable)
    {
        $akses = Auth::user();
        if(!$akses->can('pejabatsudin-show')){
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

        $data = [
            'wilayah' => $wilayah
        ];

        return $dataTable->setWilayah($wilayah->id)->renders('admin_panel.sudins.index_pejabat',[],[],$data);
    }
    
    /**
     * Show the form for creating a new Sudin.
     *
     * @return Response
     */
    public function create()
    {
        $akses = Auth::user();
        if(!$akses->can('pejabatsudin-create')){
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

        $sudin = $this->sudinRepository->where('id_wilayah', $wilayah->id)->get()->pluck('nama_sudin','id');

        if ($sudin->count() < 1) {
            Flash::error('Suku Dinas tidak ditemukan');
            return redirect(route('panel.pejabat_sudins.index'));
        }

        $jabatan = $this->jabatan->getArrayJabatan($this->tipe, @$wilayah->tipe, true);

        return view('admin_panel.sudins.create_pejabat')
            ->with('jabatan', $jabatan)
            ->with('wilayah', $wilayah)
            ->with('sukudinas', $sudin);
    }

    public function store(Request $request)
    {
        $akses = Auth::user();
        if(!$akses->can('pejabatsudin-store')){
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

        $sudin = $this->sudinRepository->findWithoutFail(@$input["id_suku_dinas"]);

        if (empty($sudin)) {
            Flash::error('Suku Dinas tidak ditemukan');
            return redirect(route('panel.pejabat_sudins.index'));
        }

        if ($sudin->id_wilayah != $wilayah->id) {
            Flash::error('Suku Dinas tidak ditemukan pada '. $wilayah->nama);
            return redirect(route('panel.pejabat_sudins.index'));
        }

        $input['created_by'] = $akses->id;
        $input['history_updated'] = savingHistory($akses);

        if (@$request->foto) {
            // $input['foto'] = $this->saveFile->setImage(@$request->foto)->setStorage('pemda')->handle();
            $input['foto'] = $this->uploadFile->uploadFile($this->path, 'foto', @$request->foto);
        } else {
            $input['foto'] = null;
        }

        $flag = null;
        foreach ($input['tahun'] as $key => $val) {
            // cek apa data sudah ada, kalau sudah ada di update datanya, kalau belom ada di insert baru
            $cek = $this->pejabatRepo->where('id_suku_dinas', $input["id_suku_dinas"])->where('id_jabatan', $input["id_jabatan"])->where('tahun', $val)->first();
            if(empty($cek)){
                $pejabatsudin = $this->pejabatRepo->create([
                    "id_suku_dinas" => $input["id_suku_dinas"],
                    "id_jabatan" => $input["id_jabatan"],
                    "nama_lengkap" => $input["nama_lengkap"],
                    "nip" => $input["nip"],
                    "contact" => $input["contact"],
                    "email" => $input["email"],
                    "tahun" => $val,
                    "foto" => $input["foto"]
                ]);
            }
            else{
                $pejabatsudin = $this->pejabatRepo->update([
                    "nama_lengkap" => $input["nama_lengkap"],
                    "nip" => $input["nip"],
                    "contact" => $input["contact"],
                    "email" => $input["email"],
                    "foto" => $input["foto"]
                ], $cek->id);
            }            
        }


        Flash::success('Pejabat Suku Dinas berhasil disimpan.');
        return redirect(route('panel.pejabat_sudins.index'));
    }

    public function edit($id)
    {
        $akses = Auth::user();
        if(!$akses->can('pejabatsudin-edit')){
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

        $sudin = $this->sudinRepository->where('id_wilayah', $wilayah->id)->get()->pluck('nama_sudin','id');

        if ($sudin->count() < 1) {
            Flash::error('Suku Dinas tidak ditemukan');
            return redirect(route('panel.pejabat_sudins.index'));
        }

        $pejabat = $this->pejabatRepo->findWithoutFail($id);

        if (empty($pejabat)) {
            Flash::error('Pejabat Suku Dinas tidak ditemukan');
            return redirect(route('panel.pejabat_sudins.index'));
        }

        $jabatan = $this->jabatan->getArrayJabatan($this->tipe, @$wilayah->tipe, true);

        return view('admin_panel.sudins.edit_pejabat')
            ->with('jabatan', $jabatan)
            ->with('wilayah', $wilayah)
            ->with('sukudinas', $sudin)
            ->with('pejabat', $pejabat);
    }

    public function update($id, Request $request)
    {
        $akses = Auth::user();
        $input = $request->all();

        if(!$akses->can('pejabatsudin-update')){
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

        $sudin = $this->sudinRepository->findWithoutFail(@$input["id_suku_dinas"]);

        if (empty($sudin)) {
            Flash::error('Suku Dinas tidak ditemukan');
            return redirect(route('panel.pejabat_sudins.index'));
        }
        
        if ($sudin->id_wilayah != $wilayah->id) {
            Flash::error('Suku Dinas tidak ditemukan pada '. $wilayah->nama);
            return redirect(route('panel.pejabat_sudins.index'));
        }

        $pejabat = $this->pejabatRepo->findWithoutFail($id);

        if (empty($pejabat)) {
            Flash::error('Pejabat Suku Dinas tidak ditemukan');
            return redirect(route('panel.pejabat_sudins.index'));
        }

        $input['updated_by'] = $akses->id;
        $input['history_updated'] = savingHistory($akses, $pejabat->history_updated);

        if (@$request->foto) {
            // $input['foto'] = $this->saveFile->setImage(@$request->foto)->setStorage('pemda')->handle();
            $input['foto'] = $this->uploadFile->uploadFile($this->path, 'foto', @$request->foto, $pejabat, 'update');
        } else {
            $input['foto'] = @$pejabat->foto;
        }
        
        $pejabatsudin = $this->pejabatRepo->update($input, @$id);

        Flash::success('Pejabat Suku Dinas berhasil disimpan.');
        return redirect(route('panel.pejabat_sudins.index'));
    }

    public function destroy($id)
    {
        $akses = Auth::user();
        if(!$akses->can('pejabatsudin-delete')){
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

        $pejabat = $this->pejabatRepo->findWithoutFail($id);

        if (empty($pejabat)) {
            Flash::error('Pejabat Suku Dinas tidak ditemukan');
            return redirect(route('panel.pejabat_sudins.index'));
        }

        $sudin = $this->sudinRepository->findWithoutFail($pejabat->id_suku_dinas);

        if (empty($sudin)){
            Flash::error('Suku Dinas tidak ditemukan');
            return redirect(route('panel.pejabat_sudins.index'));
        }

        if ($sudin->id_wilayah != $wilayah->id) {
            Flash::error('Suku Dinas tidak ditemukan pada '. $wilayah->nama);
            return redirect(route('panel.pejabat_sudins.index'));
        }

        $this->pejabatRepo->delete($id);
        Flash::success('Pejabat Suku Dinas berhasil di hapus.');
        return redirect(route('panel.pejabat_sudins.index'));
    }
}
