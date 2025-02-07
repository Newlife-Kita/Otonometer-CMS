<?php

namespace App\Http\Controllers;

use App\DataTables\Pejabatwilayah1DataTable;
use App\DataTables\PejabatwilayahDataTable;
use App\DataTables\PejabatwilayahPreviewAllDataTable;
use App\DataTables\PejabatwilayahPreviewDataTable;
use App\Export\PejabatwilayahAllTemplateExport;
use App\Export\PejabatwilayahDataExport;
use App\Export\PejabatwilayahTemplateExport;
use App\Export\PimpinanDprdAllTemplateExport;
use App\Http\Requests;
use App\Http\Requests\CreatePejabatwilayahRequest;
use App\Http\Requests\UpdatePejabatwilayahRequest;
use App\Import\PejabatwilayahImport;
use App\Repositories\PejabatwilayahRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Import\PejabatwilayahImportAll;
use App\Models\PejabatwilayahTemp;
use App\Repositories\JabatanRepository;
use App\Repositories\WilayahRepository;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\SaveFileService;
use App\Models\Pejabatwilayah;
use App\Models\Wilayah;
use App\Services\UploadFileService;
use Carbon\Carbon;

class PejabatwilayahController extends AppBaseController
{
    /** @var  PejabatwilayahRepository */
    private $pejabatwilayahRepository;
    private $tipe = 'pemda';
    private $jabatan;
    private $wilayah;
    private $saveFile;
    private $uploadFile;
    private $path = 'pemda/';

    public function __construct(PejabatwilayahRepository $pejabatwilayahRepo, JabatanRepository $jabatanRepo, WilayahRepository $wilayahRepo, SaveFileService $saveFileService)
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
        $this->saveFile = $saveFileService;
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
        return $pejabatwilayahDataTable->render('pejabatwilayahs.index');
    }

    /**
     * Show the form for creating a new Pejabatwilayah.
     *
     * @return Response
     */
    public function create($id = null)
    {
        $wilayah = $this->wilayah->findWithoutFail($id);

        if (empty($wilayah)) {
            Flash::error('Propinsi/Kabupaten/Kota not found');
            return redirect(route('pejabatwilayahs.index'));
        }

        $masterjabatan = $this->jabatan->getArrayJabatan($this->tipe, @$wilayah->tipe);

        return view('pejabatwilayahs.create')
            ->with('masterjabatan', $masterjabatan)
            ->with('wilayah', $wilayah)
            ->with('back', 'pejabatwilayahs.show');
    }

    /**
     * Show the form for uploading an excel file Pejabatwilayah
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function createExcel($id)
    {
        $wilayah = $this->wilayah->findWithoutFail($id);

        if (empty($wilayah)) {
            Flash::error('Propinsi/Kabupaten/Kota not found');
            return redirect(route('pejabatwilayahs.index'));
        }

        return view('pejabatwilayahs.upload', ['wilayah' => $wilayah, 'back' => 'pejabatwilayahs.show']);
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
        $user = Auth::user();
        $input = $request->all();
        $input['created_by'] = $user->id;
        $input['history_updated'] = savingHistory($user);

        if (strtotime(@$input["tahun_lantik"]) > strtotime(@$input["tahun_akhir"])) {
            return redirect()->back()->withInput($input)->withErrors(['Periode Awal tidak boleh lebih besar dari periode akhir']);
        }

        if (@$request->foto) {
            // $input['foto'] = $this->saveFile->setImage(@$request->foto)->setStorage('pemda')->handle();
            $input['foto'] = $this->uploadFile->uploadFile($this->path, 'pemda', @$request->foto);
        } else {
            $input['foto'] = null;
        }

        $id_wilayah = 0;
        if (empty(@$input["usetahun"])) {

            for ($a = $input['tahun_lantik']; $a < $input['tahun_akhir']; $a++) {
                $input['tahun'] = $a;
                $pejabatwilayah = $this->pejabatwilayahRepository->create($input);
                $id_wilayah = $pejabatwilayah->id_wilayah;
            }
        } else {
            // cek tahun ada di dalam periode apa gak
            $cek = false;
            for ($a = $input['tahun_lantik']; $a < $input['tahun_akhir']; $a++) {
                if ($a == $input['tahun']) $cek = true;
            }

            if ($cek == false) {
                return redirect()->back()->withInput($input)->withErrors(['Tahun tidak ada dalam range periode jabatan']);
            }

            $pejabatwilayah = $this->pejabatwilayahRepository->create($input);
            $id_wilayah = $pejabatwilayah->id_wilayah;
        }

        Flash::success('Pejabat Pemerintah Provinsi/Kab/Kota saved successfully.');
        return redirect(route('pejabatwilayahs.show', $pejabatwilayah->id_wilayah));
    }

    /**
     * Store data pejabat wilayah using excel template
     * @param \App\Http\Requests\CreatePejabatwilayahRequest $request
     * @return void
     */
    public function storeExcel(CreatePejabatwilayahRequest $request, $id)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $wilayah = $this->wilayah->findWithoutFail($id);

            $import = new PejabatwilayahImport;
            $import->setWilayah($wilayah);
            Excel::import($import, $file);
            Flash::success('File successfully uploaded');
            return redirect(route('pejabatwilayahs.preview', $id));
        } else {
            Flash::error('No File Uploaded');
            return redirect(route('Pejabatwilayahs.create_excel'));
        }
    }

    /**
     * To download template of excel
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function template($id)
    {
        $masterwilayah = $this->wilayah->findWithoutFail($id);

        if (empty($masterwilayah)) {
            Flash::error('Propinsi/Kabupaten/Kota not found');
            return redirect(route('pejabatwilayahs.create_excel'));
        }

        $tipe = ucfirst($masterwilayah->tipe);
        $nama = ucfirst($masterwilayah->nama);
        $jabatan = $this->jabatan->getArrayJabatan('pemda', $masterwilayah->tipe);


        return Excel::download(new PejabatwilayahTemplateExport($masterwilayah, $tipe), "Template_Pejabat_wilayah_{$tipe}_{$nama}.xlsx");
    }

    /**
     * Upload photo file using pop up
     */
    public function uploadPhoto(CreatePejabatwilayahRequest $request, $id)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $pejabatwilayah = PejabatwilayahTemp::find($id);

            // $pejabatwilayah->foto = $this->saveFile->setImage($file)->setStorage('pemda')->handle();
            $foto = $this->uploadFile->uploadFile($this->path, 'pemda', @$file);
            PejabatwilayahTemp::where('nama_lengkap', $pejabatwilayah->nama_lengkap)->where('tahun_lantik', @$pejabatwilayah->tahun_lantik)->where('tahun_akhir', @$pejabatwilayah->tahun_akhir)
                ->where('id_wilayah', @$pejabatwilayah->id_wilayah)->update(['foto' => $foto]);

            return response('Foto sukses terupload');
        } else {
            return response('No File Uploaded', 400);
        }
    }

    /**
     * Display the specified Pejabatwilayah.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show(Pejabatwilayah1DataTable $dataTables, $id)
    {
        $wilayah = $this->wilayah->findWithoutFail($id);

        if (empty($wilayah)) {
            Flash::error('Propinsi/Kabupaten/Kota not found');
            return redirect(route('pejabatwilayahs.index'));
        }

        return $dataTables->setWilayah($id)->renders('pejabatwilayahs.index_wilayah', [], [], ['wilayah' => $wilayah]);
    }

    /**
     * Data preview
     * @param \App\DataTables\PejabatwilayahPreviewDataTable $dataTables
     * @param mixed $id
     * @return mixed
     */
    public function preview(PejabatwilayahPreviewDatatable $dataTables, $id)
    {
        $wilayah = $this->wilayah->findWithoutFail($id);

        if (empty($wilayah)) {
            Flash::error('Propinsi/Kabupaten/Kota not found');
            return redirect(route('pejabatwilayahs.index'));
        }

        return $dataTables->setWilayah($id)->renders('pejabatwilayahs.preview', [], [], ['wilayah' => $wilayah]);
    }

    /**
     * Summary of submit
     * @param mixed $id
     * @return void
     */
    public function submit($id)
    {
        $dataTransfer = PejabatwilayahTemp::where('id_wilayah', $id)->get();

        foreach ($dataTransfer as $data) {
            Pejabatwilayah::updateOrCreate([
                'id_wilayah' => $data->id_wilayah,
                'id_jabatan' => $data->id_jabatan,
                'nama_lengkap' => $data->nama_lengkap,
                'tahun_lantik' => $data->tahun_lantik,
                'tahun_akhir' => $data->tahun_akhir,
                'tahun' => $data->tahun
            ], ['foto' => $data->foto]);
        }

        PejabatwilayahTemp::withTrashed()->where('id_wilayah', $id)->forceDelete();

        if (PejabatwilayahTemp::count() == 0) {
            PejabatwilayahTemp::truncate();
        }

        return redirect(route('pejabatwilayahs.show', ['id' => $id]));
    }

    /**
     * Summary of cancel
     * @param mixed $id
     * @return void
     */
    public function cancel($id)
    {
        PejabatwilayahTemp::withTrashed()->where('id_wilayah', $id)->forceDelete();

        if (PejabatwilayahTemp::count() == 0) {
            PejabatwilayahTemp::truncate();
        }

        return redirect(route('pejabatwilayahs.show', ['id' => $id]));
    }

    /**
     * Show the form for editing the specified Pejabatwilayah.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $pejabatwilayah = $this->pejabatwilayahRepository->findWithoutFail($id);

        if (empty($pejabatwilayah)) {
            Flash::error('Pejabat Pemerintah Daerah not found');
            return back();
        }

        $wilayah = $this->wilayah->findWithoutFail($pejabatwilayah->id_wilayah);

        $masterjabatan = $this->jabatan->getArrayJabatan($this->tipe, @$wilayah->tipe);

        return view('pejabatwilayahs.edit')
            ->with('pejabatwilayah', $pejabatwilayah)
            ->with('masterjabatan', $masterjabatan)
            ->with('wilayah', $wilayah)
            ->with('back', 'pejabatwilayahs.show');
    }

    /**
     * Summary of editPreview
     * @param mixed $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function editPreview($id)
    {
        $pejabatwilayah = PejabatwilayahTemp::find($id);

        if (empty($pejabatwilayah)) {
            Flash::error('Pejabat Pemerintah Daerah not found');
            return back();
        }

        $wilayah = $this->wilayah->findWithoutFail($pejabatwilayah->id_wilayah);

        $masterjabatan = $this->jabatan->getArrayJabatan($this->tipe, @$wilayah->tipe);

        return view('pejabatwilayahs.edit_preview')
            ->with('pejabatwilayah', $pejabatwilayah)
            ->with('masterjabatan', $masterjabatan)
            ->with('wilayah', $wilayah)
            ->with('back', 'pejabatwilayahs.preview');
    }

    /**
     * Update the specified Pejabatwilayah in storage.
     *
     * @param  int              $id
     * @param UpdatePejabatwilayahRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePejabatwilayahRequest $request)
    {
        $pejabatwilayah = $this->pejabatwilayahRepository->findWithoutFail($id);

        if (empty($pejabatwilayah)) {
            Flash::error('Pejabat Pemerintah Daerah not found');
            return back();
        }

        $input = $request->all();
        $user = Auth::user();
        $input['updated_by'] = $user->id;
        $input['history_updated'] = savingHistory($user, $pejabatwilayah->history_updated);

        if (strtotime(@$input["tahun_lantik"]) > strtotime(@$input["tahun_akhir"])) {
            return redirect()->back()->withInput($input)->withErrors(['Periode Awal tidak boleh lebih besar dari periode akhir']);
        }

        if (@$input["tahun"] > date('Y')) {
            return redirect()->back()->withInput($input)->withErrors(['Tahun tidak boleh lebih dari tahun saat ini']);
        }

        $cek = false;
        for ($a = $input['tahun_lantik']; $a <= $input['tahun_akhir']; $a++) {
            if ($a == $input['tahun']) $cek = true;
        }

        if ($cek == false) {
            return redirect()->back()->withInput($input)->withErrors(['Tahun tidak ada dalam range periode jabatan']);
        }

        if (@$request->foto) {
            // $input['foto'] = $this->saveFile->setModel(@$pejabatwilayah->foto)->setImage(@$request->foto)->setStorage('pemda')->isDelete(1)->handle();
            $input['foto'] = $this->uploadFile->uploadFile($this->path, 'foto', @$request->foto, $pejabatwilayah, 'update');
        } else {
            $input['foto'] = @$pejabatwilayah->foto;
        }

        $pejabatwilayah = $this->pejabatwilayahRepository->update($input, $id);

        Pejabatwilayah::where('nama_lengkap', $pejabatwilayah->nama_lengkap)
            ->where('id_wilayah', @$pejabatwilayah->id_wilayah)->update(['foto' => $input['foto']]);



        Flash::success('Pejabat Pemerintah Daerah updated successfully.');
        return redirect(route('pejabatwilayahs.show', $pejabatwilayah->id_wilayah));
    }

    /**
     * Update the specified Pejabatwilayah in storage.
     *
     * @param  int              $id
     * @param UpdatePejabatwilayahRequest $request
     *
     * @return Response
     */
    public function previewUpdate($id, UpdatePejabatwilayahRequest $request)
    {
        $pejabat = PejabatwilayahTemp::find($id);

        if (empty($pejabat)) {
            Flash::error('Pejabat Pemerintah Daerah not found');
            return back();
        }

        $input = $request->all();
        $user = Auth::user();
        $input['updated_by'] = $user->id;

        if (@$request->foto) {
            $input['foto'] = $this->uploadFile->uploadFile($this->path, 'foto', @$request->foto, $pejabat, 'update');
        } else {
            $input['foto'] = @$pejabat->foto;
        }

        $pejabat->update(['tahun' => $input['tahun']]);

        unset($input["_method"]);
        unset($input["_token"]);
        unset($input['tahun']);

        PejabatwilayahTemp::where('nama_lengkap', @$pejabat->nama_lengkap)->where('tahun_lantik', @$pejabat->tahun_lantik)->where('tahun_akhir', @$pejabat->tahun_akhir)
            ->where('id_wilayah', @$pejabat->id_wilayah)->where('id_jabatan', @$pejabat->id_jabatan)->update($input);

        Flash::success('Pejabat Pemerintah Daerah updated successfully.');
        return redirect(route('pejabatwilayahs.preview', $pejabat->id_wilayah));
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
        $pejabatwilayah = $this->pejabatwilayahRepository->findWithoutFail($id);
        $user = Auth::user();

        if (empty($pejabatwilayah)) {
            Flash::error('Pejabat Pemerintah Daerah not found');
            return back();
        }

        $updated = [
            'history_updated' => savingHistory($user, $pejabatwilayah->history_updated, true)
        ];

        $this->pejabatwilayahRepository->update($updated, $id);

        $this->pejabatwilayahRepository->delete($id);

        Flash::success('Pejabat Pemerintah Daerah deleted successfully.');
        return redirect(route('pejabatwilayahs.show', $pejabatwilayah->id_wilayah));
    }

    /**
     * Remove the specified Pejabatwilayah from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function previewDelete($id)
    {
        $pejabatwilayah = PejabatwilayahTemp::find($id);
        $user = Auth::user();

        if (empty($pejabatwilayah)) {
            Flash::error('Pejabat Pemerintah Daerah not found');
            return back();
        }

        $pejabatwilayah->delete();

        Flash::success('Pejabat Pemerintah Daerah deleted successfully.');
        return redirect(route('pejabatwilayahs.preview', $pejabatwilayah->id_wilayah));
    }

    /**
     * Store data Pejabatwilayah from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function ($reader) {
            $reader->each(function ($item) {
                $pejabatwilayah = $this->pejabatwilayahRepository->create($item->toArray());
            });
        });

        Flash::success('Pejabat Pemerintah Daerah saved successfully.');
        return redirect(route('pejabatwilayahs.index'));
    }

    /* UPLOAD FOR ALL WILAYAH */

    /**
     * For upload data pimpinan for all wilayah
     *
     *
     */

    public function createExcelALL()
    {
        $akses = Auth::user();
        if (!$akses->can('pejabatwilayah-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        return view('pejabatwilayahs.upload_all');
    }


    public function storeExcelAll(UpdatePejabatwilayahRequest $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $import = new PejabatwilayahImportAll;

            Excel::import($import, $file);
            Flash::success('File successfully uploaded');
            return redirect(route('pejabatwilayahs.preview_all'));
        } else {
            Flash::error('No File Uploaded');
            return redirect(route('pejabatwilayahs.create_excel_all'));
        }
    }

    public function templateAll()
    {
        $akses = Auth::user();
        if (!$akses->can('pejabatwilayah-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }


        $wilayah = Wilayah::all();

        return Excel::download(new PejabatwilayahAllTemplateExport($wilayah), "Template_Pejabat_Wilayah_Seluruh_wilayah.xlsx");
    }

    public function previewAll(PejabatwilayahPreviewAllDataTable $dataTables)
    {
        $akses = Auth::user();
        if (!$akses->can('pejabatwilayah-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        return $dataTables->renders('pejabatwilayahs.preview_all', [], [], []);
    }

    /**
     * Summary of cancel
     * @param mixed $id
     * @return void
     */
    public function cancelAll()
    {
        PejabatwilayahTemp::withTrashed()->forceDelete();

        if (PejabatwilayahTemp::count() == 0) {
            PejabatwilayahTemp::truncate();
        }

        return redirect(route('pejabatwilayahs.index'));
    }

    /**
     * Summary of submit
     * @param mixed $id
     * @return void
     */
    public function submitAll()
    {
        $dataTransfer =  PejabatwilayahTemp::all();

        foreach ($dataTransfer as $data) {
            Pejabatwilayah::updateOrCreate([
                'id_wilayah' => $data->id_wilayah,
                'id_jabatan' => $data->id_jabatan,
                'nama_lengkap' => $data->nama_lengkap,
                'tahun_lantik' => $data->tahun_lantik,
                'tahun_akhir' => $data->tahun_akhir,
                'tahun' => $data->tahun
            ], ['foto' => $data->foto]);
        }

        PejabatwilayahTemp::withTrashed()->forceDelete();

        if (PejabatwilayahTemp::count() == 0) {
            PejabatwilayahTemp::truncate();
        }

        return redirect(route('pejabatwilayahs.index'));
    }

    public function previewUpdateAll($id, UpdatePejabatwilayahRequest $request)
    {
        $pejabat = PejabatwilayahTemp::find($id);

        if (empty($pejabat)) {
            Flash::error('Pejabat wilayah not found');
            return back();
        }


        $input = $request->all();

        if (@$request->foto) {
            $input['foto'] = $this->uploadFile->uploadFile($this->path, 'foto', @$request->foto, $pejabat, 'update');
        } else {
            $input['foto'] = @$pejabat->foto;
        }

        $pejabat->update(['tahun' => $input['tahun']]);

        unset($input["_method"]);
        unset($input["_token"]);
        unset($input['tahun']);

        PejabatwilayahTemp::where('nama_lengkap', @$pejabat->nama_lengkap)->where('tahun_lantik', @$pejabat->tahun_lantik)->where('tahun_akhir', @$pejabat->tahun_akhir)
            ->where('id_wilayah', @$pejabat->id_wilayah)->where('id_jabatan', @$pejabat->id_jabatan)->update($input);

        Flash::success('Pejabat updated successfully.');
        return redirect(route('pejabatwilayahs.preview_all'));
    }

    public function editPreviewAll($id)
    {
        $akses = Auth::user();
        if (!$akses->can('pejabatwilayah-edit')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $pejabat = PejabatwilayahTemp::find($id);

        if (empty($pejabat)) {
            Flash::error('Pimpinan pejabat not found');
            return back();
        }

        $wilayah = $this->wilayah->findWithoutFail($pejabat->id_wilayah);

        if (empty($wilayah)) {
            Flash::error('Provinsi/Kab/Kota not found');
            return back();
        }

        $masterjabatan = $this->jabatan->getArrayJabatan($this->tipe, @$wilayah->tipe);

        return view('pejabatwilayahs.edit_preview_all')
            ->with('masterjabatan', $masterjabatan)
            ->with('wilayah', $wilayah)
            ->with('pejabatwilayah', $pejabat)
            ->with('back', 'pejabatwilayahs.preview_all');
    }

    public function previewDeleteAll($id)
    {
        $pejabat = PejabatwilayahTemp::find($id);

        if (empty($pejabat)) {
            Flash::error('Pejabat Wilayah not found');
            return back();
        }

        $pejabat->delete();

        Flash::success('Pejabat wilayah deleted successfully.');
        return redirect(route('pejabatwilayahs.preview_all'));
    }

    public function downloadAll()
    {
        $akses = Auth::user();
        if (!$akses->can('pejabatwilayah-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        $timestamp = Carbon::now()->timestamp;

        return Excel::download(new PejabatwilayahDataExport(), "Data_Pejabat_Wilayah_{$timestamp}.xlsx");
    }
}
