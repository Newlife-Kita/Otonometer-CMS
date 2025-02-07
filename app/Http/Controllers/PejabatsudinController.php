<?php

namespace App\Http\Controllers;

use App\DataTables\Pejabatsudin1DataTable;
use App\DataTables\PejabatsudinDataTable;
use App\DataTables\Pejabatsudin2DataTable;
use App\DataTables\PejabatsudinPreviewDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatePejabatsudinRequest;
use App\Http\Requests\UpdatePejabatsudinRequest;
use App\Repositories\PejabatsudinRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\JabatanRepository;
use App\Repositories\SudinRepository;
use App\Repositories\WilayahRepository;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Export\PejabatsudinExport;
use App\Import\PejabatsudinImport;
use App\Models\PejabatsudinTemp;
use App\Models\Pejabatsudin;
use App\Services\SaveFileService;

class PejabatsudinController extends AppBaseController
{
    /** @var  PejabatsudinRepository */
    private $pejabatsudinRepository;
    private $jabatan;
    private $tipe = 'dinas';
    private $sudin;
    private $saveFile;
    private $wilayah;

    public function __construct(PejabatsudinRepository $pejabatsudinRepo, SaveFileService $saveFileService, JabatanRepository $jabatanRepo, SudinRepository $sudinRepo, WilayahRepository $wilayahRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:pejabatsudin-edit', ['only' => ['edit']]);
        $this->middleware('can:pejabatsudin-store', ['only' => ['store']]);
        $this->middleware('can:pejabatsudin-show', ['only' => ['show']]);
        $this->middleware('can:pejabatsudin-update', ['only' => ['update']]);
        $this->middleware('can:pejabatsudin-delete', ['only' => ['delete']]);
        $this->middleware('can:pejabatsudin-create', ['only' => ['create']]);
        $this->pejabatsudinRepository = $pejabatsudinRepo;
        $this->jabatan = $jabatanRepo;
        $this->sudin = $sudinRepo;
        $this->saveFile = $saveFileService;
        $this->wilayah = $wilayahRepo;
    }

    /**
     * Display a listing of the Pejabatsudin.
     *
     * @param PejabatsudinDataTable $pejabatsudinDataTable
     * @return Response
     */
    public function index(PejabatsudinDataTable $pejabatsudinDataTable)
    {
        return $pejabatsudinDataTable->render('pejabatsudins.index');
    }

    public function show(Pejabatsudin1DataTable $dataTables, $id)
    {
        $wilayah = $this->wilayah->findWithoutFail($id);

        if (empty($wilayah)) {
            Flash::error('Propinsi/Kabupaten/Kota not found');
            return redirect(route('pejabatwilayahs.index'));
        }

        return $dataTables->setWilayah($id)->renders('pejabatsudins.index_wilayah', [], [], ['wilayah' => $wilayah]);
    }

    /**
     * Show the form for creating a new Pejabatsudin.
     *
     * @return Response
     */
    public function create($id = null)
    {
        $sukudinas = $this->sudin->with('wilayah')->findWithoutFail($id);

        if (empty($sukudinas)) {
            Flash::error('Suku dinas not found');
            return redirect(route('pejabatsudins.index'));
        }
        $masterjabatan = $this->jabatan->getArrayJabatan($this->tipe, @$sukudinas->wilayah->tipe, true);

        return view('pejabatsudins.create')
            ->with('masterjabatan', $masterjabatan)
            ->with('sukudinas', $sukudinas);
    }

    /**
     * Store a newly created Pejabatsudin in storage.
     *
     * @param CreatePejabatsudinRequest $request
     *
     * @return Response
     */
    public function store(CreatePejabatsudinRequest $request)
    {
        $user = Auth::user();
        $input = $request->all();
        $input['created_by'] = $user->id;
        $input['history_updated'] = savingHistory($user);

        if (@$request->foto) {
            $input['foto'] = $this->saveFile->setImage(@$request->foto)->setStorage('pejabatsudin')->handle();
        } else {
            $input['foto'] = null;
        }

        $pejabatsudin = $this->pejabatsudinRepository->create($input);

        Flash::success('Pejabat Suku Dinas saved successfully.');
        return redirect(route('pejabatsudins.dinas', @$pejabatsudin->id_suku_dinas));
    }

    /**
     * Display the specified Pejabatsudin.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function dinas(Pejabatsudin2DataTable $pejabatDataTable, $id)
    {
        $sudin = $this->sudin->with('wilayah')->findWithoutFail($id);

        if (empty($sudin)) {
            Flash::error('Suku dinas not found');
            return redirect(route('pejabatsudins.index'));
        }

        return $pejabatDataTable->setSudin($id)->renders('pejabatsudins.indexsudin', [], [], ['sudin' => $sudin]);
    }

    /**
     * Show the form for editing the specified Pejabatsudin.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $pejabatsudin = $this->pejabatsudinRepository->findWithoutFail($id);

        if (empty($pejabatsudin)) {
            Flash::error('Pejabat Suku Dinas not found');
            return back();
        }

        $sukudinas = $this->sudin->with('wilayah')->findWithoutFail($pejabatsudin->id_suku_dinas);

        if (empty($sukudinas)) {
            Flash::error('Suku dinas not found');
            return back();
        }

        $masterjabatan = $this->jabatan->getArrayJabatan($this->tipe, @$sukudinas->wilayah->tipe, true);

        return view('pejabatsudins.edit')
            ->with('pejabatsudin', $pejabatsudin)
            ->with('masterjabatan', $masterjabatan)
            ->with('sukudinas', $sukudinas);
    }

    /**
     * Update the specified Pejabatsudin in storage.
     *
     * @param  int              $id
     * @param UpdatePejabatsudinRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePejabatsudinRequest $request)
    {
        $pejabatsudin = $this->pejabatsudinRepository->findWithoutFail($id);

        if (empty($pejabatsudin)) {
            Flash::error('Pejabat Suku Dinas not found');
            return redirect(route('pejabatsudins.index'));
        }

        $input = $request->all();
        $user = Auth::user();
        $input['updated_by'] = $user->id;
        $input['history_updated'] = savingHistory($user, $pejabatsudin->history_updated);

        if (@$request->foto) {
            $input['foto'] = $this->saveFile->setModel(@$pejabatsudin->foto)->setImage(@$request->foto)->setStorage('pejabatsudin')->isDelete(1)->handle();
        } else {
            $input['foto'] = @$pejabatsudin->foto;
        }

        $pejabatsudin = $this->pejabatsudinRepository->update($input, $id);

        Flash::success('Pejabat Suku Dinas updated successfully.');
        return redirect(route('pejabatsudins.dinas', @$pejabatsudin->id_suku_dinas));
    }

    /**
     * Remove the specified Pejabatsudin from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $pejabatsudin = $this->pejabatsudinRepository->findWithoutFail($id);
        $user = Auth::user();

        if (empty($pejabatsudin)) {
            Flash::error('Pejabat Suku Dinas not found');
            return redirect(route('pejabatsudins.index'));
        }

        $updated = [
            'history_updated' => savingHistory($user, $pejabatsudin->history_updated, true)
        ];

        $this->pejabatsudinRepository->update($updated, $id);

        $this->pejabatsudinRepository->delete($id);

        Flash::success('Pejabat Suku Dinas deleted successfully.');
        return redirect(route('pejabatsudins.dinas', @$pejabatsudin->id_suku_dinas));
    }

    public function createExcel($id)
    {
        $sudin = $this->sudin->findWithoutFail($id);

        if (empty($sudin)) {
            Flash::error('Pejabat Suku Dinas not found');
            return back();
        }

        return view('pejabatsudins.upload', ['sudin' => $sudin]);
    }

    public function template($id)
    {
        $sudin = $this->sudin->findWithoutFail($id);

        if (empty($sudin)) {
            Flash::error('Suku Dinas not found');
            return back();
        }

        $wilayah = $this->wilayah->findWithoutFail($sudin->id_wilayah);

        if (empty($wilayah)) {
            Flash::error('Wilayah not found');
            return back();
        }

        $tipe = ucfirst($wilayah->tipe);
        $nama = ucfirst($wilayah->nama);

        $jabatan = $this->jabatan->getArrayJabatan('dinas', $wilayah->tipe, true);

        return Excel::download(new PejabatsudinExport($jabatan), "Template_Pejabat_wilayah_{$sudin->nama_sudin}_{$tipe}_{$nama}.xlsx");
    }

    /**
     * Summary of storeExcel
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storeExcel(CreatePejabatsudinRequest $request, $id)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $sudin = $this->sudin->findWithoutFail($id);
            $wilayah = $this->wilayah->findWithoutFail($sudin->id_wilayah);

            $import = new PejabatsudinImport;
            $import->setSudin($sudin);
            $import->setWilayah($wilayah);
            Excel::import($import, $file);
            Flash::success('File successfully uploaded');
            return redirect(route('pejabatsudins.preview', $id));
        } else {
            Flash::error('No File Uploaded');
            return redirect(route('Pejabatsudins.create_excel', $id));
        }
    }

    public function preview(PejabatsudinPreviewDataTable $dataTables, $id)
    {
        $sudin = $this->sudin->findWithoutFail($id);

        if (empty($sudin)) {
            Flash::error('Suku dinas not found');
            return redirect(route('pejabatsudins.index'));
        }

        return $dataTables->setSudin($id)->renders('pejabatsudins.preview', [], [], ['sudin' => $sudin]);
    }

    public function editPreview($id)
    {
        $pejabatsudin = PejabatsudinTemp::find($id);

        if (empty($pejabatsudin)) {
            Flash::error('Pejabat sudin not found');
            return back();
        }

        $sukudinas = $this->sudin->with('wilayah')->findWithoutFail($pejabatsudin->id_suku_dinas);

        if (empty($sukudinas)) {
            Flash::error('Suku dinas not found');
            return back();
        }

        $masterjabatan = $this->jabatan->getArrayJabatan($this->tipe, @$sukudinas->wilayah->tipe, true);

        return view('pejabatsudins.preview_edit')
            ->with('pejabatsudin', $pejabatsudin)
            ->with('masterjabatan', $masterjabatan)
            ->with('sukudinas', $sukudinas);
    }

    public function previewUpdate(UpdatePejabatsudinRequest $request, $id)
    {
        $pejabatsudin = PejabatsudinTemp::find($id);

        if (empty($pejabatwilayah)) {
            Flash::error('Pejabat sudin not found');
            return back();
        }

        $input = $request->all();

        if (@$request->foto) {
            $input['foto'] = $this->saveFile->setModel(@$pejabatwilayah->foto)->setImage(@$request->foto)->setStorage('pejabatsudin')->handle();
        } else {
            $input['foto'] = @$pejabatwilayah->foto;
        }

        PejabatwilayahTemp::find($id)->update($input);

        Flash::success('Pejabat Pprov/Kab/Kota updated successfully.');
        return redirect(route('pejabatsudins.preview', $pejabatwilayah->id_suku_dinas));
    }

    public function previewDelete($id)
    {
        $pejabatsudin = PejabatsudinTemp::find($id);

        if (empty($pejabatsudin)) {
            Flash::error('Pejabat sudin DPRD not found');
            return back();
        }

        $pejabatsudin->delete();

        Flash::success('Pejabat sudin deleted successfully.');
        return redirect(route('pejabatsudins.preview', $pejabatsudin->id_suku_dinas));
    }

    public function uploadPhoto(UpdatePejabatsudinRequest $request, $id)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $pejabatsudin = PejabatsudinTemp::find($id);
            $foto = $this->saveFile->setModel(@$pejabatsudin->foto)->setImage($file)->setStorage('pejabatsudin')->handle();

            PejabatsudinTemp::where('nama_lengkap', $pejabatsudin->nama_lengkap)->where('nip', $pejabatsudin->nip)
                ->update(['foto' => $foto]);

            return response('Foto sukses terupload');
        } else {
            return response('No File Uploaded', 400);
        }
    }

    public function submit($id)
    {
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

        return redirect(route('pejabatsudins.dinas', $id));
    }

    public function cancel($id)
    {
        PejabatsudinTemp::withTrashed()->where('id_suku_dinas', $id)->forceDelete();

        if (PejabatsudinTemp::count() == 0) {
            PejabatsudinTemp::truncate();
        }

        return redirect(route('pejabatsudins.dinas', $id));
    }



    /**
     * Store data Pejabatsudin from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function ($reader) {
            $reader->each(function ($item) {
                $pejabatsudin = $this->pejabatsudinRepository->create($item->toArray());
            });
        });

        Flash::success('Pejabatsudin saved successfully.');
        return redirect(route('pejabatsudins.index'));
    }
}
