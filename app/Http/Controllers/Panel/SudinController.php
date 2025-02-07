<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests;
use App\DataTables\Panel\PejabatsudinDataTable;
use App\DataTables\Panel\SudinDataTable;
use App\Http\Requests\CreateSudinRequest;
use App\Http\Requests\UpdateSudinRequest;
use App\Models\Pejabatsudin;
use App\Repositories\BahasaRepository;
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

class SudinController extends AppBaseController
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

    public function __construct(WilayahRepository $wilayahRepo, SudinRepository $sudinRepo, JabatanRepository $jabatanRepo, PejabatsudinRepository $pejabatsudinRepo, BahasaRepository $bahasaRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:sudin-edit', ['only' => ['edit']]);
        $this->middleware('can:sudin-store', ['only' => ['store']]);
        $this->middleware('can:sudin-show', ['only' => ['show']]);
        $this->middleware('can:sudin-update', ['only' => ['update']]);
        $this->middleware('can:sudin-delete', ['only' => ['delete']]);
        $this->middleware('can:sudin-create', ['only' => ['create']]);
        $this->wilayah = $wilayahRepo;
        $this->sudinRepository = $sudinRepo;
        $this->jabatan = $jabatanRepo;
        $this->pejabatRepo = $pejabatsudinRepo;
        $this->bahasaRepository = $bahasaRepo;
        $this->uploadFile = new UploadFileService();
    }

    /**
     * Display a listing of the Sudin.
     *
     * @param SudinDataTable $sudinDataTable
     * @return Response
     */
    public function index(SudinDataTable $sudinDataTable)
    {
        $akses = Auth::user();
        if(!$akses->can('sudin-show')){
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

        return $sudinDataTable->setWilayah($wilayah->id)->renders('admin_panel.sudins.index',[],[],$data);
    }    

    /**
     * Show the form for creating a new Sudin.
     *
     * @return Response
     */
    public function create()
    {
        $akses = Auth::user();
        if(!$akses->can('sudin-create')){
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

        $bahasa = $this->bahasaRepository->all();
        return view('admin_panel.sudins.create')
            ->with('wilayah', $wilayah)
            ->with('bahasa', $bahasa);
    }

    /**
     * Store a newly created Sudin in storage.
     *
     * @param CreateSudinRequest $request
     *
     * @return Response
     */
    public function store(CreateSudinRequest $request)
    {
        $akses = Auth::user();
        if(!$akses->can('sudin-store')){
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

        $user = Auth::user();
        $input = $request->all();
        $input['created_by'] = $user->id;
        $input['history_updated'] = savingHistory($user);

        if (@$request->logo) {
            // $input['logo'] = $this->saveFile->setImage(@$request->logo)->setStorage('sudin')->handle();
            $input['logo'] = $this->uploadFile->uploadFile($this->path, 'sudin', @$request->logo);
        } else {
            $input['logo'] = null;
        }

        $sudin = $this->sudinRepository->create($input);

        Flash::success('Suku Dinas '. @$wilayah->nama .' berhasil disimpan');
        return redirect(route('panel.sudins.index'));
    }

    /**
     * Show the form for editing the specified Sudin.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $akses = Auth::user();
        if(!$akses->can('sudin-edit')){
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

        $sudin = $this->sudinRepository->findWithoutFail($id);

        if (empty($sudin)) {
            Flash::error('Suku Dinas tidak ditemukan');
            return redirect(route('panel.sudins.index'));
        }

        if($sudin->id_wilayah != $wilayah->id){
            Flash::error('Suku Dinas tidak ditemukan');
            return redirect(route('panel.sudins.index'));
        }

        $bahasa = $this->bahasaRepository->all();
        return view('admin_panel.sudins.edit')
            ->with('sudin', $sudin)
            ->with('wilayah', $wilayah)
            ->with('bahasa', $bahasa);
    }

    /**
     * Update the specified Sudin in storage.
     *
     * @param  int              $id
     * @param UpdateSudinRequest $request
     *
     * @return Response
     */
    public function update(UpdateSudinRequest $request, $id)
    {
        $akses = Auth::user();
        if(!$akses->can('sudin-update')){
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

        $sudin = $this->sudinRepository->findWithoutFail($id);

        if (empty($sudin)) {
            Flash::error('Suku Dinas tidak ditemukan');
            return redirect(route('panel.sudins.index'));
        }

        if($sudin->id_wilayah != $wilayah->id){
            Flash::error('Suku Dinas tidak ditemukan');
            return redirect(route('panel.sudins.index'));
        }

        $input = $request->all();
        $sudin = $this->sudinRepository->findWithoutFail($id);

        if (empty($sudin)) {
            Flash::error('Suku Dinas tidak ditemukan');
            return redirect(route('sudins.show', $wilayah->id));
        }

        $user = Auth::user();
        $input['updated_by'] = $user->id;
        $input['history_updated'] = savingHistory($user, $sudin->history_updated);

        if (@$request->logo) {
            // $input['logo'] = $this->saveFile->setModel(@$sudin->logo)->setImage(@$request->logo)->setStorage('sudin')->isDelete(1)->handle();
            $input['logo'] = $this->uploadFile->uploadFile($this->path, 'logo', $request->logo, $sudin, 'update');
        } else {
            $input['logo'] = @$sudin->logo;
        }

        $sudin = $this->sudinRepository->update($input, $id);

        Flash::success('Suku Dinas updated successfully.');
        return redirect(route('sudins.show', $wilayah->id));
    }

    /**
     * Remove the specified Sudin from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $akses = Auth::user();
        if(!$akses->can('sudin-delete')){
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

        $sudin = $this->sudinRepository->findWithoutFail($id);

        if (empty($sudin)) {
            Flash::error('Suku Dinas tidak ditemukan');
            return redirect(route('panel.sudins.index'));
        }

        if($sudin->id_wilayah != $wilayah->id){
            Flash::error('Suku Dinas tidak ditemukan');
            return redirect(route('panel.sudins.index'));
        }

        $user = Auth::user();

        if (empty($sudin)) {
            Flash::error('Suku Dinas tidak ditemukan');
            return redirect(route('sudins.index'));
        }

        $updated = [
            'history_updated' => savingHistory($user, $sudin->history_updated, true)
        ];

        $this->sudinRepository->update($updated, $id);
        $this->pejabatRepo->where('id_suku_dinas', $id)->delete();
        $this->sudinRepository->delete($id);

        Flash::success('Suku Dinas berhasil dihapus.');
        return redirect(route('panel.sudins.index'));
    }
}
