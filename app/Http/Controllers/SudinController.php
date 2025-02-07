<?php

namespace App\Http\Controllers;

use App\DataTables\PejabatsudinDataTable;
use App\DataTables\PejabatsudinPreviewDataTable;
use App\DataTables\SudinDataTable;
use App\DataTables\SudinPreviewDataTable;
use App\DataTables\SudinshowDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateSudinRequest;
use App\Http\Requests\UpdateSudinRequest;
use App\Repositories\SudinRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BahasaRepository;
use App\Repositories\JabatanRepository;
use App\Repositories\PejabatsudinRepository;
use App\Repositories\WilayahRepository;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\SaveFileService;
use App\Services\UploadFileService;
use App\Export\PejabatsudinTemplateExport;
use App\Export\SudinExport;
use App\Import\PejabatsudinImport;
use App\Import\SudinImport;
use App\Models\PejabatsudinTemp;
use App\Models\Pejabatsudin;
use App\Models\SudinTemp;

class SudinController extends AppBaseController
{
    /** @var  SudinRepository */
    private $sudinRepository;
    private $saveFile;
    private $jabatan;
    private $wilayah;
    private $pejabatRepo;
    private $bahasaRepository;
    private $uploadFile;
    private $path = 'sudin/';
    private $tipe = 'dinas';

    public function __construct(SudinRepository $sudinRepo, SaveFileService $saveFileService, WilayahRepository $wilayahRepo, BahasaRepository $bahasaRepo, JabatanRepository $jabatanRepo, PejabatsudinRepository $pejabatsudinRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:sudin-edit', ['only' => ['edit']]);
        $this->middleware('can:sudin-store', ['only' => ['store']]);
        $this->middleware('can:sudin-show', ['only' => ['show']]);
        $this->middleware('can:sudin-update', ['only' => ['update']]);
        $this->middleware('can:sudin-delete', ['only' => ['delete']]);
        $this->middleware('can:sudin-create', ['only' => ['create']]);
        $this->sudinRepository = $sudinRepo;
        $this->saveFile = $saveFileService;
        $this->jabatan = $jabatanRepo;
        $this->wilayah = $wilayahRepo;
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
        return $sudinDataTable->render('sudins.index');
    }

    public function show(SudinshowDataTable $dataTable, $id)
    {
        $wilayah = $this->wilayah->findWithoutFail($id);

        if (empty($wilayah)) {
            Flash::error('Provinsi/Kab/Kota not found');
            return redirect(route('sudins.index'));
        }

        return $dataTable->setWilayah(@$wilayah->id)->renders('sudins.show', ['wilayah' => $wilayah]);
    }

    /**
     * Show the form for creating a new Sudin.
     *
     * @return Response
     */
    public function create($id = null)
    {
        $wilayah = $this->wilayah->findWithoutFail($id);

        if (empty($wilayah)) {
            Flash::error('Provinsi/Kab/Kota not found');
            return redirect(route('sudins.index'));
        }

        $bahasa = $this->bahasaRepository->all();
        return view('sudins.create')
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

        Flash::success('Suku Dinas saved successfully.');
        return redirect(route('sudins.show', @$input["id_wilayah"]));
    }

    /**
     * Show the form for editing the specified Sudin.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id_wilayah, $id)
    {
        $wilayah = $this->wilayah->findWithoutFail($id_wilayah);

        if (empty($wilayah)) {
            Flash::error('Provinsi/Kab/Kota not found');
            return redirect(route('sudins.index'));
        }

        $sudin = $this->sudinRepository->findWithoutFail($id);

        if (empty($sudin)) {
            Flash::error('Suku Dinas not found');
            return redirect(route('sudins.show', @$id_wilayah));
        }

        $bahasa = $this->bahasaRepository->all();
        return view('sudins.edit')
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
        $input = $request->all();

        $wilayah = $this->wilayah->findWithoutFail(@$input["id_wilayah"]);

        if (empty($wilayah)) {
            Flash::error('Provinsi/Kab/Kota not found');
            return redirect(route('sudins.index'));
        }

        $sudin = $this->sudinRepository->findWithoutFail($id);

        if (empty($sudin)) {
            Flash::error('Suku Dinas not found');
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
        $sudin = $this->sudinRepository->findWithoutFail($id);
        $user = Auth::user();

        if (empty($sudin)) {
            Flash::error('Suku Dinas not found');
            return redirect(route('sudins.index'));
        }

        $updated = [
            'history_updated' => savingHistory($user, $sudin->history_updated, true)
        ];

        $this->sudinRepository->update($updated, $id);
        $this->sudinRepository->delete($id);

        Flash::success('Suku Dinas deleted successfully.');
        return redirect(route('sudins.show', $sudin->id_wilayah));
    }

    public function pejabat_show(PejabatsudinDataTable $dataTable, $id, $id2)
    {

        $wilayah = $this->wilayah->findWithoutFail($id);

        if (empty($wilayah)) {
            Flash::error('Provinsi/Kab/Kota not found');
            return redirect(route('sudins.index'));
        }

        $sudin = $this->sudinRepository->findWithoutFail($id2);

        if (empty($sudin)) {
            Flash::error('Suku Dinas not found');
            return redirect(route('sudins.show', $id));
        }

        return $dataTable->setSudin(@$sudin->id)->renders('sudins.show_pejabat', ['wilayah' => $wilayah, 'sudin' => $sudin]);
    }

    /**
     * Show the form for creating a new Sudin.
     *
     * @return Response
     */
    public function pejabat_create($id, $id2)
    {
        $wilayah = $this->wilayah->findWithoutFail($id);

        if (empty($wilayah)) {
            Flash::error('Provinsi/Kab/Kota not found');
            return redirect(route('sudins.index'));
        }

        $sudin = $this->sudinRepository->findWithoutFail($id2);

        if (empty($sudin)) {
            Flash::error('Suku Dinas not found');
            return redirect(route('sudins.show', $wilayah->id));
        }

        $masterjabatan = $this->jabatan->getArrayJabatan($this->tipe, @$sudin->wilayah->tipe, true);

        return view('sudins.create_pejabat')
            ->with('masterjabatan', $masterjabatan)
            ->with('wilayah', $wilayah)
            ->with('sukudinas', $sudin);
    }

    public function store_pejabat(Request $request)
    {
        $user = Auth::user();
        $input = $request->all();

        $wilayah = $this->wilayah->findWithoutFail(@$input["id_wilayah"]);

        if (empty($wilayah)) {
            Flash::error('Provinsi/Kab/Kota not found');
            return redirect(route('sudins.index'));
        }

        $sudin = $this->sudinRepository->findWithoutFail(@$input["id_suku_dinas"]);

        if (empty($sudin)) {
            Flash::error('Suku Dinas not found');
            return redirect(route('sudins.show', $wilayah->id));
        }

        $input['created_by'] = $user->id;
        $input['history_updated'] = savingHistory($user);

        if (strtotime(@$input["tahun_lantik"]) > strtotime(@$input["tahun_akhir"])) {
            return redirect()->back()->withInput($input)->withErrors(['Periode Awal tidak boleh lebih besar dari periode akhir']);
        }

        if (@$request->foto) {
            // $input['foto'] = $this->saveFile->setImage(@$request->foto)->setStorage('pemda')->handle();
            $input['foto'] = $this->uploadFile->uploadFile($this->path, 'foto', @$request->foto);
        } else {
            $input['foto'] = null;
        }

        if (empty(@$input["usetahun"])) {

            for ($a = $input['tahun_lantik']; $a <= $input['tahun_akhir']; $a++) {
                $input['tahun'] = $a;
                $pejabatsudin = $this->pejabatRepo->create($input);
            }
        } else {
            $cek = false;
            for ($a = $input['tahun_lantik']; $a <= $input['tahun_akhir']; $a++) {
                if ($a == $input['tahun']) $cek = true;
            }

            if ($cek == false) {
                return redirect()->back()->withInput($input)->withErrors(['Tahun tidak ada dalam range periode jabatan']);
            }

            $pejabatsudin = $this->pejabatRepo->create($input);
        }

        Flash::success('Pejabat Suku Dinas saved successfully.');
        return redirect(route('sudins.pejabat', [$wilayah->id, $sudin->id]));
    }

    public function edit_pejabat(Request $request, $id, $id2, $id3)
    {
        $wilayah = $this->wilayah->findWithoutFail($id);

        if (empty($wilayah)) {
            Flash::error('Provinsi/Kab/Kota not found');
            return redirect(route('sudins.index'));
        }

        $sudin = $this->sudinRepository->findWithoutFail($id2);

        if (empty($sudin)) {
            Flash::error('Suku Dinas not found');
            return redirect(route('sudins.show', $wilayah->id));
        }

        $pejabat = $this->pejabatRepo->findWithoutFail($id3);

        if (empty($pejabat)) {
            Flash::error('Pejabat Suku Dinas not found');
            return redirect(route('sudins.pejabat', [$id, $id2]));
        }

        $masterjabatan = $this->jabatan->getArrayJabatan($this->tipe, @$sudin->wilayah->tipe, true);

        return view('sudins.edit_pejabat')
            ->with('masterjabatan', $masterjabatan)
            ->with('wilayah', $wilayah)
            ->with('sukudinas', $sudin)
            ->with('pejabatsudin', $pejabat);
    }

    public function update_pejabat(Request $request)
    {
        $user = Auth::user();
        $input = $request->all();
        $input['created_by'] = $user->id;
        $input['history_updated'] = savingHistory($user);

        $input = $request->all();

        $wilayah = $this->wilayah->findWithoutFail(@$input["id_wilayah"]);

        if (empty($wilayah)) {
            Flash::error('Provinsi/Kab/Kota not found');
            return redirect(route('sudins.index'));
        }

        $sudin = $this->sudinRepository->findWithoutFail(@$input["id_suku_dinas"]);

        if (empty($sudin)) {
            Flash::error('Suku Dinas not found');
            return redirect(route('sudins.show', $wilayah->id));
        }

        $pejabat = $this->pejabatRepo->findWithoutFail(@$input["id"]);

        if (empty($pejabat)) {
            Flash::error('Pejabat Suku Dinas not found');
            return redirect(route('sudins.pejabat', [$wilayah->id, $sudin->id]));
        }

        if (strtotime(@$input["tahun_lantik"]) > strtotime(@$input["tahun_akhir"])) {
            return redirect()->back()->withInput($input)->withErrors(['Periode Awal tidak boleh lebih besar dari periode akhir']);
        }

        $cek = false;
        for ($a = $input['tahun_lantik']; $a <= $input['tahun_akhir']; $a++) {
            if ($a == $input['tahun']) $cek = true;
        }

        if ($cek == false) {
            return redirect()->back()->withInput($input)->withErrors(['Tahun tidak ada dalam range periode jabatan']);
        }

        if (@$request->foto) {
            // $input['foto'] = $this->saveFile->setImage(@$request->foto)->setStorage('pemda')->handle();
            $input['foto'] = $this->uploadFile->uploadFile($this->path, 'pejabatsudin', @$request->foto, $pejabat, 'update');
        } else {
            $input['foto'] = @$pejabat->foto;
        }

        $pejabatsudin = $this->pejabatRepo->update($input, @$input['id']);

        Pejabatsudin::where('nama_lengkap', $pejabatsudin->nama_lengkap)->where('nip', $pejabatsudin->nip)
            ->update(['foto' => $input['foto']]);

        Flash::success('Pejabat Suku Dinas saved successfully.');
        return redirect(route('sudins.pejabat', [$wilayah->id, $sudin->id]));
    }

    public function destroy_pejabat(Request $request, $id, $id2, $id3)
    {
        $wilayah = $this->wilayah->findWithoutFail($id);

        if (empty($wilayah)) {
            Flash::error('Provinsi/Kab/Kota not found');
            return redirect(route('sudins.index'));
        }

        $sudin = $this->sudinRepository->findWithoutFail($id2);

        if (empty($sudin)) {
            Flash::error('Suku Dinas not found');
            return redirect(route('sudins.show', $wilayah->id));
        }

        $pejabat = $this->pejabatRepo->findWithoutFail($id3);

        if (empty($pejabat)) {
            Flash::error('Pejabat Suku Dinas not found');
            return redirect(route('sudins.pejabat', [$id, $id2]));
        }

        $this->pejabatRepo->delete($id3);
        Flash::success('Pejabat Suku Dinas deleted successfully.');
        return redirect(route('sudins.pejabat', [$wilayah->id, $sudin->id]));
    }

    public function template($id)
    {
        return view('sudins.show_fields')->with('id', $id);
    }

    public function download(Request $request)
    {
        return Excel::download(new SudinExport(), "Template_Suku_Dinas.xlsx");
    }

    /**
     * Summary of storeExcel
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function upload(Request $request, $id)
    {
        $wilayah = $this->wilayah->findWithoutFail(@$id);

        if (empty($wilayah)) {
            return redirect()->back()->withInput($request->all())->withErrors(['Provinsi/Kab/Kota not found']);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $import = new SudinImport;
            $import->setWilayah($wilayah);
            Excel::import($import, $file);
            Flash::success('File successfully uploaded');
            return redirect(route('sudins.preview', $id));
        } else {
            Flash::error('No File Uploaded');
            return redirect(route('sudins.template', $id));
        }
    }

    public function preview(SudinPreviewDataTable $datatables, $id)
    {
        $wilayah = $this->wilayah->findWithoutFail($id);

        if (empty($wilayah)) {
            return back()->withErrors('Cannot find wilayah');
        }


        return $datatables->setWilayah($id)->renders('sudins.preview', [], [], ['wilayah' => $wilayah]);
    }

    public function destroy_preview($id)
    {
        $sudin = SudinTemp::find($id);

        if (empty($sudin)) {
            Flash::error('Sudin not found');
            return back();
        }

        $sudin->delete();

        Flash::success('Sudin deleted successfully.');
        return redirect(route('sudins.preview', $sudin->id_wilayah));
    }

    public function edit_preview($id)
    {


        $sudin = SudinTemp::find($id);

        if (empty($sudin)) {
            Flash::error('Suku Dinas not found');
            return back();
        }

        $wilayah = $this->wilayah->findWithoutFail($sudin->id_wilayah);

        if (empty($wilayah)) {
            Flash::error('Provinsi/Kab/Kota not found');
            return back();
        }

        $bahasa = $this->bahasaRepository->all();
        return view('sudins.edit_preview')
            ->with('sudin', $sudin)
            ->with('wilayah', $wilayah)
            ->with('bahasa', $bahasa);
    }

    public function update_preview(Request $request, $id)
    {
        $input = $request->all();

        $wilayah = $this->wilayah->findWithoutFail(@$input["id_wilayah"]);

        if (empty($wilayah)) {
            Flash::error('Provinsi/Kab/Kota not found');
            return redirect(route('sudins.index'));
        }

        $sudin = SudinTemp::find($id);

        if (empty($sudin)) {
            Flash::error('Suku Dinas not found');
            return redirect(route('sudins.show', $wilayah->id));
        }

        if (@$request->logo) {
            // $input['logo'] = $this->saveFile->setModel(@$sudin->logo)->setImage(@$request->logo)->setStorage('sudin')->isDelete(1)->handle();
            $input['logo'] = $this->uploadFile->uploadFile($this->path, 'logo', $request->logo, $sudin, 'update');
        } else {
            $input['logo'] = @$sudin->logo;
        }

        $sudin->update($input);

        Flash::success('Suku Dinas updated successfully.');
        return redirect(route('sudins.preview', $wilayah->id));
    }

    public function upload_logo(Request $request, $id)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $sudin = SudinTemp::find($id);
            $sudin->logo = $this->uploadFile->uploadFile($this->path, 'foto', $file);

            $sudin->save();
            return response('Foto sukses terupload');
        } else {
            return response('No File Uploaded', 400);
        }
    }

    public function submit($id)
    {
        $dataTransfer = SudinTemp::where('id_wilayah', 'id')->get();

        foreach ($dataTransfer as $data) {
            Sudin::updateOrCreate([
                'nama_sudin' => $data->nama_sudin,
                'id_wilayah' => $data->id_wilayah
            ], [
                'alamat' => $data->alamat,
                'logo' => $data->logo,
                'nama_pic' => $data->nama_pic,
                'email_pic' => $data->email_pic,
                'telp_pic' => $data->telp_pic
            ]);
        }

        SudinTemp::where('id_wilayah', $id)->SudinTemp::withTrashed()->where('id_wilayah', $id)->forceDelete();

        if (SudinTemp::count() == 0) {
            SudinTemp::truncate();
        }

        return redirect(route('sudins.index'));
    }

    public function cancel($id)
    {

        SudinTemp::withTrashed()->where('id_wilayah', $id)->forceDelete();

        if (SudinTemp::count() == 0) {
            SudinTemp::truncate();
        }

        return redirect(route('sudins.index'));
    }

    public function template_pejabat($id)
    {
        $sudin = $this->sudinRepository->findWithoutFail($id);
        if (empty($sudin)) {
            return back()->withErrors('Cannot find relaated suku dinas');
        }

        $wilayah = $this->wilayah->findWithoutFail($sudin->id_wilayah);

        if (empty($wilayah)) {
            return back()->withErrors('Cannot find related wilayah');
        }


        return view('sudins.pejabat_sudin_upload')
            ->with('id', $id)
            ->with('wilayah', $wilayah)
            ->with('sudin', $sudin);
    }

    public function upload_pejabat(Request $request, $id)
    {
        $sudin = $this->sudinRepository->findWithoutFail($id);
        $wilayah = $this->wilayah->findWithoutFail($sudin->id_wilayah);

        if (empty($sudin)) {
            return redirect()->back()->withInput($request->all())->withErrors(['Sudin is not found']);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $import = new PejabatsudinImport;
            $import->setSudin($sudin);
            $import->setWilayah($wilayah);
            Excel::import($import, $file);
            Flash::success('File successfully uploaded');
            return redirect(route('sudins.preview_pejabat', $id));
        } else {
            return redirect()->back()->withInput($request->all())->withErrors(['Cannot upload data']);
        }
    }

    public function preview_pejabat(PejabatsudinPreviewDataTable $datatables, $id)
    {
        $sudin = $this->sudinRepository->findWithoutFail($id);

        if (empty($sudin)) {
            return redirect()->back()->withInput($request->all())->withErrors(['Sudin is not found']);
        }

        return $datatables->setSudin($id)->renders('sudins.preview_pejabat', [], [], ['sudin' => $sudin]);
    }

    public function download2($id)
    {
        $sudin = $this->sudinRepository->findWithoutFail($id);
        $wilayah = $this->wilayah->findWithoutFail($sudin?->id_wilayah);

        if (empty($wilayah)) {
            Flash::error('Provinsi/Kab/Kota not found');
            return redirect(route('sudins.template'));
        }

        $jabatan = $this->jabatan->getArrayJabatan('dinas', $wilayah->tipe, true);

        return Excel::download(new PejabatsudinTemplateExport($jabatan), "Template_Pejabat_sudin.xlsx");
    }

    public function submit_pejabat($id)
    {
        $wilayah = $this->sudinRepository->findWithoutFail($id)->id_wilayah;
        $dataTransfer = PejabatsudinTemp::where('id_suku_dinas', $id)->get();

        foreach ($dataTransfer as $data) {
            Pejabatsudin::updateOrCreate([
                'nama_lengkap' => $data->nama_lengkap,
                'id_suku_dinas' => $data->id_suku_dinas,
                'tahun' => $data->tahun,
                'tahun_lantik' => $data->tahun_lantik,
                'tahun_akhir' => $data->tahun_akhir,
            ], [
                'id_jabatan' => $data->id_jabatan,
                'nip' => $data->nip,
                'contact' => $data->contact,
                'email' => $data->email,
                'foto' => $data->foto
            ]);
        }

        PejabatsudinTemp::withTrashed()->where('id_suku_dinas', $id)->forceDelete();

        if (PejabatsudinTemp::count() == 0) {
            PejabatsudinTemp::truncate();
        }

        return redirect(route('sudins.pejabat', [$wilayah, $id]));
    }

    public function cancel_pejabat($id)
    {
        $wilayah = $this->sudinRepository->findWithoutFail($id)->id_wilayah;
        PejabatsudinTemp::withTrashed()->where('id_suku_dinas', $id)->forceDelete();

        if (PejabatsudinTemp::count() == 0) {
            PejabatsudinTemp::truncate();
        }

        return redirect(route('sudins.pejabat', [$wilayah, $id]));
    }

    public function upload_photo_pejabat(Request $request, $id)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $pejabatsudin = PejabatsudinTemp::find($id);
            $foto = $this->uploadFile->uploadFile($this->path, 'foto', $file);

            PejabatsudinTemp::where('nama_lengkap', $pejabatsudin->nama_lengkap)->where('nip', $pejabatsudin->nip)
                ->update(['foto' => $foto]);

            return response('Foto sukses terupload');
        } else {
            return response('No File Uploaded', 400);
        }
    }

    public function edit_preview_pejabat($id)
    {
        $pejabatsudin = PejabatsudinTemp::find($id);

        if (empty($pejabatsudin)) {
            Flash::error('Pejabat sudin not found');
            return back();
        }

        $sukudinas = $this->sudinRepository->with('wilayah')->findWithoutFail($pejabatsudin->id_suku_dinas);

        if (empty($sukudinas)) {
            Flash::error('Suku dinas not found');
            return back();
        }

        $masterjabatan = $this->jabatan->getArrayJabatan($this->tipe, @$sukudinas->wilayah?->tipe, true);

        $wilayah = $this->wilayah->findWithoutFail($sukudinas->id_wilayah);

        return view('sudins.edit_preview_pejabat')
            ->with('pejabatsudin', $pejabatsudin)
            ->with('masterjabatan', $masterjabatan)
            ->with('sukudinas', $sukudinas)
            ->with('wilayah', $wilayah);
    }

    public function update_preview_pejabat(Request $request, $id)
    {
        $user = Auth::user();
        $input = $request->all();
        $input['created_by'] = $user->id;
        $input['history_updated'] = savingHistory($user);

        $input = $request->all();

        $wilayah = $this->wilayah->findWithoutFail(@$input["id_wilayah"]);

        if (empty($wilayah)) {
            Flash::error('Provinsi/Kab/Kota not found');
            return redirect(route('sudins.index'));
        }

        $sudin = $this->sudinRepository->findWithoutFail(@$input["id_suku_dinas"]);

        if (empty($sudin)) {
            Flash::error('Suku Dinas not found');
            return redirect(route('sudins.show', $wilayah->id));
        }

        $pejabat = $this->pejabatRepo->findWithoutFail(@$input["id"]);

        if (empty($pejabat)) {
            Flash::error('Pejabat Suku Dinas not found');
            return redirect(route('sudins.pejabat', [$wilayah->id, $sudin->id]));
        }

        if (strtotime(@$input["tahun_lantik"]) > strtotime(@$input["tahun_akhir"])) {
            return redirect()->back()->withInput($input)->withErrors(['Periode Awal tidak boleh lebih besar dari periode akhir']);
        }

        $cek = false;
        for ($a = $input['tahun_lantik']; $a < $input['tahun_akhir']; $a++) {
            if ($a == $input['tahun']) $cek = true;
        }

        if ($cek == false) {
            return redirect()->back()->withInput($input)->withErrors(['Tahun tidak ada dalam range periode jabatan']);
        }

        if (@$request->foto) {
            // $input['foto'] = $this->saveFile->setImage(@$request->foto)->setStorage('pemda')->handle();
            $input['foto'] = $this->uploadFile->uploadFile($this->path, 'pejabatsudin', @$request->foto, $pejabat, 'update');
        } else {
            $input['foto'] = @$pejabat->foto;
        }

        PejabatsudinTemp::find($id)->update($input);
        Flash::success('Pejabat Suku Dinas saved successfully.');
        return redirect(route('sudins.pejabat', [$wilayah->id, $sudin->id]));
    }

    public function destroy_preview_pejabat($id)
    {
        $pejabatsudin = PejabatsudinTemp::find($id);

        if (empty($pejabatsudin)) {
            Flash::error('Pejabat sudin not found');
            return back();
        }

        $pejabatsudin->delete();

        Flash::success('Pejabat sudin deleted successfully.');
        return redirect(route('sudins.preview_pejabat', $pejabatsudin->id_suku_dinas));
    }
}
