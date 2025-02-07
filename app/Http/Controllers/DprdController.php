<?php

namespace App\Http\Controllers;

use App\DataTables\Dprd1DataTable;
use App\DataTables\DprdDataTable;
use App\DataTables\DprdPreviewDataTable;
use App\Export\DprdDataExport;
use App\Export\DprdTemplateExport;
use App\Http\Requests;
use App\Http\Requests\CreateDprdRequest;
use App\Http\Requests\UpdateDprdRequest;
use App\Import\DprdImport;
use App\Repositories\DprdRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\JabatanRepository;
use App\Repositories\KomisiRepository;
use App\Repositories\PartaiRepository;
use App\Repositories\WilayahRepository;
use App\Services\SaveFileService;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\DprdTemp;
use App\Models\Dprd;
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
    private $saveFile;
    private $uploadFile;
    private $path = 'dprd/';

    public function __construct(DprdRepository $dprdRepo, JabatanRepository $jabatRepo, WilayahRepository $wilayahRepo, PartaiRepository $partaiRepo, KomisiRepository $komisiRepo, SaveFileService $saveFileService)
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
        $this->saveFile = $saveFileService;

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
        return $dprdDataTable->render('dprds.index');
    }

    /**
     * Show the form for creating a new Dprd.
     *
     * @return Response
     */
    public function create($id = null)
    {
        $wilayah = $this->wilayah->findWithoutFail($id);

        if (empty($wilayah)) {
            Flash::error('Provinsi/Kab/Kota not found');
            return redirect(route('dprds.index'));
        }

        $masterjabatan = $this->jabatan->getArrayJabatan($this->tipe, @$wilayah->tipe, true);
        $masterParpol = $this->parpol->getArrayPartai();
        $masterKomisi = $this->komisi->getArrayKomisi();

        return view('dprds.create')->with('komisi', $masterKomisi)->with('parpol', $masterParpol)
            ->with('jabatan', $masterjabatan)
            ->with('wilayah', $wilayah);
    }

    /**
     * Show the form for uploading an excel file DPRD
     * @param mixed $id
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

        return view('dprds.upload', ['wilayah' => $wilayah]);
    }

    /**
     *
     * Store data DPRD using excel template
     * @param \App\Http\Requests\CreateDprdRequest $request
     *
     * @param mixed $id
     * @return void
     *
     */
    public function storeExcel(CreateDprdRequest $request, $id)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $wilayah = $this->wilayah->findWithoutFail($id);

            $import = new DprdImport;
            $import->setWilayah($wilayah);
            Excel::import($import, $file);
            Flash::success('File successfully uploaded');
            return redirect(route('dprds.preview', $id));
        } else {
            Flash::error('No File Uploaded');
            return redirect(route('dprds.create_excel'));
        }
    }

    public function template($id)
    {
        $masterwilayah = $this->wilayah->findWithoutFail($id);

        if (empty($masterwilayah)) {
            Flash::error('Propinsi/Kabupaten/Kota not found');
            return redirect(route('pejabatwilayahs.create_excel'));
        }

        $tipe = ucfirst($masterwilayah->tipe);
        $nama = ucfirst($masterwilayah->nama);
        $jabatan = $this->jabatan->getArrayJabatan($this->tipe, $masterwilayah->tipe, true);
        $komisi = $this->komisi->getArrayKomisi();
        $partai = $this->parpol->getArrayPartai();

        return Excel::download(new DprdTemplateExport($jabatan, $komisi, $partai, $masterwilayah), "Template_DPRD_{$tipe}_{$nama}.xlsx");
    }

    /**
     * Upload photo file using pop up
     */
    public function uploadPhoto(CreateDprdRequest $request, $id)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $dprd = DprdTemp::find($id);
            $foto = $this->uploadFile->uploadFile($this->path, 'dprd', $file);

            DprdTemp::where('nama_lengkap', @$dprd->nama_lengkap)->where('tahun_lantik', @$dprd->tahun_lantik)->where('tahun_akhir', @$dprd->tahun_akhir)
                ->where('id_wilayah', @$dprd->id_wilayah)->update(['foto' => $foto]);

            return response('Foto sukses terupload');
        } else {
            return response('No File Uploaded', 400);
        }
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
        $user = Auth::user();
        $input = $request->all();
        $input['created_by'] = $user->id;
        $input['history_updated'] = savingHistory($user);

        if (strtotime(@$input["tahun_lantik"]) > strtotime(@$input["tahun_akhir"])) {
            return redirect()->back()->withInput($input)->withErrors(['Periode Awal tidak boleh lebih besar dari periode akhir']);
        }

        if (@$request->foto) {
            // $input['foto'] = $this->saveFile->setImage(@$request->foto)->setStorage('dprd')->isDelete(1)->handle();
            $input['foto'] = $this->uploadFile->uploadFile($this->path, 'dprd', @$request->foto);
        } else {
            $input['foto'] = null;
        }

        $id_wilayah = 0;
        if (empty(@$input["usetahun"])) {

            for ($a = $input['tahun_lantik']; $a <= $input['tahun_akhir']; $a++) {
                $input['tahun'] = $a;
                $dprd = $this->dprdRepository->create($input);
                $id_wilayah = $dprd->id_wilayah;
            }
        } else {
            // cek tahun ada di dalam periode apa gak
            $cek = false;
            for ($a = $input['tahun_lantik']; $a <= $input['tahun_akhir']; $a++) {
                if ($a == $input['tahun']) $cek = true;
            }

            if ($cek == false) {
                return redirect()->back()->withInput($input)->withErrors(['Tahun tidak ada dalam range periode jabatan']);
            }

            $dprd = $this->dprdRepository->create($input);
            $id_wilayah = $dprd->id_wilayah;
        }

        Flash::success('Anggota DPRD saved successfully.');
        return redirect(route('dprds.show', $id_wilayah));
    }

    /**
     * Display the specified Dprd.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show(Dprd1DataTable $dataTables, $id)
    {
        $wilayah = $this->wilayah->findWithoutFail($id);

        if (empty($wilayah)) {
            Flash::error('Provinsi/Kab/Kota not found');
            return redirect(route('dprds.index'));
        }

        return $dataTables->setWilayah($id)->renders('dprds.show', [], [], ['wilayah' => $wilayah]);
    }

    /**
     * Data preview
     * @param \App\DataTables\PejabatwilayahPreviewDataTable $dataTables
     * @param mixed $id
     * @return mixed
     */
    public function preview(DprdPreviewDatatable $dataTables, $id)
    {
        $wilayah = $this->wilayah->findWithoutFail($id);

        if (empty($wilayah)) {
            Flash::error('Propinsi/Kabupaten/Kota not found');
            return redirect(route('pejabatwilayahs.index'));
        }

        return $dataTables->setWilayah($id)->renders('dprds.preview', [], [], ['wilayah' => $wilayah]);
    }

    /**
     * Summary of submit
     * @param mixed $id
     * @return void
     */
    public function submit($id)
    {
        $dataTransfer = DprdTemp::where('id_wilayah', $id)->whereNotNull('id_komisi')->get();

        foreach ($dataTransfer as $data) {
            Dprd::updateOrCreate([
                'id_wilayah' => $data->id_wilayah,
                'id_jabatan' => $data->id_jabatan,
                'nama_lengkap' => $data->nama_lengkap,
                'id_partai' => $data->id_partai,
                'id_komisi' => $data->id_komisi,
                'tahun_lantik' => $data->tahun_lantik,
                'tahun_akhir' => $data->tahun_akhir,
                'tahun' => $data->tahun
            ], ['foto' => $data->foto]);
        }

        DprdTemp::withTrashed()->where('id_wilayah', $id)->whereNotNull('id_komisi')->forceDelete();

        if (DprdTemp::count() == 0) {
            DprdTemp::truncate();
        }

        return redirect(route('dprds.show', ['id' => $id]));
    }

    /**
     * Summary of cancel
     * @param mixed $id
     * @return void
     */
    public function cancel($id)
    {
        DprdTemp::withTrashed()->where('id_wilayah', $id)->whereNotNull('id_komisi')->forceDelete();

        if (DprdTemp::count() == 0) {
            DprdTemp::truncate();
        }

        return redirect(route('dprds.show', ['id' => $id]));
    }

    /**
     * Show the form for editing the specified Dprd.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $dprd = $this->dprdRepository->findWithoutFail($id);

        if (empty($dprd)) {
            Flash::error('Anggota DPRD not found');
            return redirect(route('dprds.index'));
        }

        $wilayah = $this->wilayah->findWithoutFail($dprd->id_wilayah);

        if (empty($wilayah)) {
            Flash::error('Provinsi/Kab/Kota not found');
            return redirect(route('dprds.index'));
        }

        $masterjabatan = $this->jabatan->getArrayJabatan($this->tipe, @$wilayah->tipe, true);
        $masterParpol = $this->parpol->getArrayPartai();
        $masterKomisi = $this->komisi->getArrayKomisi();

        return view('dprds.edit')->with('komisi', $masterKomisi)->with('parpol', $masterParpol)
            ->with('jabatan', $masterjabatan)
            ->with('wilayah', $wilayah)
            ->with('dprd', $dprd);
    }

    /**
     * Show the form for editing the specified Dprd.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function editPreview($id)
    {
        $dprd = DprdTemp::find($id);

        if (empty($dprd)) {
            Flash::error('Anggota DPRD not found');
            return back();
        }

        $wilayah = $this->wilayah->findWithoutFail($dprd->id_wilayah);

        if (empty($wilayah)) {
            Flash::error('Provinsi/Kab/Kota not found');
            return back();
        }

        $masterjabatan = $this->jabatan->getArrayJabatan($this->tipe, @$wilayah->tipe, true);
        $masterParpol = $this->parpol->getArrayPartai();
        $masterKomisi = $this->komisi->getArrayKomisi();

        return view('dprds.preview_edit')->with('komisi', $masterKomisi)->with('parpol', $masterParpol)
            ->with('jabatan', $masterjabatan)
            ->with('wilayah', $wilayah)
            ->with('dprd', $dprd)
            ->with('back', 'dprds.preview');
    }

    /**
     * Update the specified Dprd in storage.
     *
     * @param  int              $id
     * @param UpdateDprdRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDprdRequest $request)
    {
        $dprd = $this->dprdRepository->findWithoutFail($id);

        if (empty($dprd)) {
            Flash::error('Anggota DPRD not found');
            return redirect(route('dprds.index'));
        }

        $input = $request->all();
        $user = Auth::user();
        $input['updated_by'] = $user->id;
        $input['history_updated'] = savingHistory($user, $dprd->history_updated);

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
            // $input['foto'] = $this->saveFile->setModel(@$dprd->foto)->setImage(@$request->foto)->setStorage('dprd')->isDelete(1)->handle();
            $input['foto'] = $this->uploadFile->uploadFile($this->path, 'foto', @$request->foto, $dprd, 'update');
        } else {
            $input['foto'] = @$dprd->foto;
        }

        $dprd = $this->dprdRepository->update($input, $id);

        Dprd::where('nama_lengkap', @$dprd->nama_lengkap)->where('tahun_lantik', @$dprd->tahun_lantik)->where('tahun_akhir', @$dprd->tahun_akhir)
            ->where('id_wilayah', @$dprd->id_wilayah)->where('id_jabatan', @$dprd->id_jabatan)->update(['foto' => $input['foto']]);

        Flash::success('Anggota DPRD updated successfully.');
        return redirect(route('dprds.show', $dprd->id_wilayah));
    }

    /**
     * Update the specified Dprd in storage.
     *
     * @param  int              $id
     * @param UpdateDprdRequest $request
     *
     * @return Response
     */
    public function previewUpdate($id, UpdateDprdRequest $request)
    {
        $dprd = DprdTemp::find($id);

        if (empty($dprd)) {
            Flash::error('Anggota DPRD not found');
            return back();
        }

        $input = $request->all();

        if (@$request->foto) {
            $input['foto'] = $this->saveFile->setModel(@$dprd->foto)->setImage(@$request->foto)->setStorage('dprd')->isDelete(1)->handle();
        } else {
            $input['foto'] = @$dprd->foto;
        }

        $dprd->update($input);

        Flash::success('Anggota DPRD updated successfully.');
        return redirect(route('dprds.preview', $dprd->id_wilayah));
    }

    /**
     * Remove the specified Dprd from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $dprd = $this->dprdRepository->findWithoutFail($id);

        if (empty($dprd)) {
            Flash::error('Anggota DPRD not found');
            return back();
        }

        $this->dprdRepository->delete($id);

        Flash::success('Anggota DPRD deleted successfully.');
        return redirect(route('dprds.show', $dprd->id_wilayah));
    }

    /**
     * Remove the specified Dprd from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function previewDelete($id)
    {
        $dprd = DprdTemp::find($id);

        if (empty($dprd)) {
            Flash::error('Anggota DPRD not found');
            return back();
        }

        $dprd->delete();

        Flash::success('Anggota DPRD deleted successfully.');
        return redirect(route('dprds.preview', $dprd->id_wilayah));
    }

    /**
     * Store data Dprd from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function ($reader) {
            $reader->each(function ($item) {
                $dprd = $this->dprdRepository->create($item->toArray());
            });
        });

        Flash::success('Anggota DPRD saved successfully.');
        return redirect(route('dprds.index'));
    }

    public function downloadAll()
    {
        $akses = Auth::user();
        if (!$akses->can('dprd-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        $timestamp = Carbon::now()->timestamp;

        return Excel::download(new DprdDataExport(), "Data_DPRD_{$timestamp}.xlsx");
    }
}
