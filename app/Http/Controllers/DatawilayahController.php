<?php

namespace App\Http\Controllers;

use App\DataTables\DatawilayahDataTable;
use App\DataTables\Datawilayah1DataTable;
use App\DataTables\DatawilayahPreviewDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateDatawilayahRequest;
use App\Http\Requests\UpdateDatawilayahRequest;
use App\Models\DatawilayahTemp;
use App\Repositories\DatawilayahRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BidangRepository;
use App\Repositories\EkonomiRepository;
use App\Repositories\SettingRepository;
use App\Repositories\WilayahRepository;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Export\DataWilayahExport;
use App\Import\DataWilayahImport;
use App\Models\Datawilayah;

class DatawilayahController extends AppBaseController
{
    /** @var  DatawilayahRepository */
    private $datawilayahRepository;
    private $wilayah;
    private $pdrb;

    private $settingRepository;

    public function __construct(DatawilayahRepository $datawilayahRepo, WilayahRepository $wilayahRepo, EkonomiRepository $ekonomiRepo, SettingRepository $settingRepository)
    {
        $this->middleware('auth');
        $this->middleware('can:datawilayah-edit', ['only' => ['edit']]);
        $this->middleware('can:datawilayah-store', ['only' => ['store']]);
        $this->middleware('can:datawilayah-show', ['only' => ['show']]);
        $this->middleware('can:datawilayah-update', ['only' => ['update']]);
        $this->middleware('can:datawilayah-delete', ['only' => ['delete']]);
        $this->middleware('can:datawilayah-create', ['only' => ['create']]);
        $this->datawilayahRepository = $datawilayahRepo;
        $this->wilayah = $wilayahRepo;
        $this->pdrb = $ekonomiRepo;
        $this->settingRepository = $settingRepository;
    }

    /**
     * Display a listing of the Datawilayah.
     *
     * @param DatawilayahDataTable $datawilayahDataTable
     * @return Response
     */
    public function index(DatawilayahDataTable $datawilayahDataTable)
    {
        $akses = Auth::user();
        if (!$akses->can('datawilayah-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return $datawilayahDataTable->render('datawilayahs.index');
    }

    /**
     * Show the form for creating a new Datawilayah.
     *
     * @return Response
     */
    public function createExcel()
    {
        $akses = Auth::user();
        if (!$akses->can('datawilayah-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $awal = $this->settingRepository->where('key', 'periode-tahun-awal')->first()->value;
        $current = $this->datawilayahRepository->selectRaw('distinct(tahun)')->get()->pluck('tahun')->toArray();
        for ($a = date('Y'); $a >= intval($awal); $a--) {
            $tahun[$a] = $a;
        }

        return view('datawilayahs.upload')->with('tahun', $tahun);
    }

    /**
     * Store a newly created Datawilayah in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function storeExcel(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $tahun = $request->tahun;

            $import = new DataWilayahImport;
            $import->setYear($tahun);
            Excel::import($import, $file);
            Flash::success('File successfully uploaded');
            return redirect(route('datawilayahs.preview', $tahun));
        } else {
            Flash::error('No File Uploaded');
            return redirect(route('datawilayahs.create_excel'));
        }
    }

    /**
     * Summary of preview
     * @param \App\DataTables\DatawilayahPreviewDataTable $dataTables
     * @param mixed $tahun
     * @return mixed
     */
    public function preview(DatawilayahPreviewDataTable $dataTables, $tahun)
    {
        $akses = Auth::user();
        if (!$akses->can('datawilayah-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return $dataTables->setTahun($tahun)->renders('datawilayahs.preview', [], [], ['tahun' => $tahun]);
    }


    public function editPreview($id)
    {
        $akses = Auth::user();
        if (!$akses->can('datawilayah-edit')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $datawilayah = DatawilayahTemp::find($id);
        $masterwilayah = $this->wilayah->getArrayOptionsWilayah();
        $mastersektor = $this->pdrb->getArrayPDRB();


        if (empty($datawilayah)) {
            Flash::error('Data wilayah is not found');
            return back();
        }

        return view('datawilayahs.edit_preview')
            ->with('datawilayah', $datawilayah)
            ->with('idWilayah', @$datawilayah->id)
            ->with('masterwilayah', $masterwilayah)
            ->with('mastersektor', $mastersektor);
    }

    public function previewUpdate($id, UpdateDatawilayahRequest $request)
    {
        $datawilayah = DatawilayahTemp::find($id);

        if (empty($datawilayah)) {
            Flash::error('Informasi Daerah/Tahun not found');
            return back();
        }

        $input = $request->all();

        $datawilayah->update($input);

        Flash::success('Data wilayah updated successfully.');
        return redirect(route('datawilayahs.preview', $datawilayah->tahun));
    }

    /**
     * Summary of previewDelete
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function previewDelete($id)
    {
        $datawilayah = DatawilayahTemp::find($id);

        if (empty($datawilayah)) {
            Flash::error('Informasi Daerah/Tahun not found');
            return back();
        }

        $datawilayah->delete();

        Flash::success('Informasi Daerah/Tahun deleted successfully.');
        return redirect(route('datawilayahs.preview', $datawilayah->tahun));
    }

    public function submit($tahun)
    {
        $dataTransfer = DatawilayahTemp::where('tahun', $tahun)->get();

        foreach ($dataTransfer as $data) {

            $updateData = [];

            $data = $data->toArray();

            foreach ($data as $field => $value) {

                // Skip 'id_wilayah', 'tahun', and 'id_sektor' fields
                if (in_array($field, ['id_wilayah', 'tahun'])) {
                    continue;
                }

                // Add field to $updateData only if its value is not null
                if ($value !== null) {
                    $updateData[$field] = $value;
                }
            }


            Datawilayah::updateOrCreate([
                'id_wilayah' => $data["id_wilayah"],
                'tahun' => $data["tahun"],
            ], $updateData);
        }

        DatawilayahTemp::withTrashed()->where('tahun', $tahun)->forceDelete();

        if (DatawilayahTemp::count() == 0) {
            DatawilayahTemp::truncate();
        }

        return redirect(route('datawilayahs.index'));
    }

    public function cancel($tahun)
    {
        DatawilayahTemp::withTrashed()->where('tahun', $tahun)->forceDelete();

        if (DatawilayahTemp::count() == 0) {
            DatawilayahTemp::truncate();
        }

        return redirect(route('datawilayahs.index'));
    }

    public function create($id)
    {
        $akses = Auth::user();
        if (!$akses->can('datawilayah-create')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $wilayah = $this->wilayah->findWithoutFail($id);
        $masterwilayah = $this->wilayah->getArrayOptionsWilayah();
        $mastersektor = $this->pdrb->getArrayPDRB();

        if (empty($wilayah)) {
            Flash::error('Propinsi/Kabupaten/Kota not found');
            return back();
        }

        return view('datawilayahs.create')
            ->with('idWilayah', $id)
            ->with('masterwilayah', $masterwilayah)
            ->with('mastersektor', $mastersektor);
    }

    public function store(CreateDatawilayahRequest $request)
    {
        $user = Auth::user();
        $input = $request->all();
        $input['created_by'] = $user->id;
        $input['history_updated'] = savingHistory($user);

        $this->datawilayahRepository->create($input);

        Flash::success('Informasi Daerah/Tahun created successfully.');
        return redirect(route('datawilayahs.show', $input['id_wilayah']));
    }


    /**
     * Summary of show
     * @param \App\DataTables\Datawilayah1DataTable $dataTables
     * @param mixed $id
     * @return mixed
     */
    public function show(Datawilayah1DataTable $dataTables, $id)
    {
        $akses = Auth::user();
        if (!$akses->can('datawilayah-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $wilayah = $this->wilayah->findWithoutFail($id);

        if (empty($wilayah)) {
            Flash::error('Propinsi/Kabupaten/Kota not found');
            return redirect(route('datawilayahs.index'));
        }

        return $dataTables->setWilayah($id)->renders('datawilayahs.index_wilayah', [], [], ['wilayah' => $wilayah]);
    }

    /**
     * To download template of excel
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function template()
    {
        $akses = Auth::user();
        if (!$akses->can('datawilayah-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $masterWilayah = $this->wilayah->select('kode', 'nama', 'tipe')->get();
        $masterEkonomi = $this->pdrb->getArrayPDRB();

        $data = [
            'wilayah' => $masterWilayah,
            'sektor' => $masterEkonomi
        ];

        return Excel::download(new DataWilayahExport($data), 'Template_Data_Wilayah_pertahun.xlsx');
    }

    /**
     * Show the form for editing the specified Datawilayah.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $akses = Auth::user();
        if (!$akses->can('datawilayah-edit')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        $masterwilayah = $this->wilayah->getArrayOptionsWilayah();
        $mastersektor = $this->pdrb->getArrayPDRB();


        $datawilayah = $this->datawilayahRepository->findWithoutFail($id);

        if (empty($datawilayah)) {
            Flash::error('Informasi Daerah/Tahun not found');
            return redirect(route('datawilayahs.index'));
        }

        return view('datawilayahs.edit')
            ->with('datawilayah', $datawilayah)
            ->with('idWilayah', $datawilayah->id_wilayah)
            ->with('masterwilayah', $masterwilayah)
            ->with('mastersektor', $mastersektor);
        // ->with('', $)
        // ->with('', $);
    }

    /**
     * Update the specified Datawilayah in storage.
     *
     * @param  int              $id
     * @param UpdateDatawilayahRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDatawilayahRequest $request)
    {
        $datawilayah = $this->datawilayahRepository->findWithoutFail($id);

        if (empty($datawilayah)) {
            Flash::error('Informasi Daerah/Tahun not found');
            return redirect(route('datawilayahs.index'));
        }

        $input = $request->all();
        $user = Auth::user();
        $input['updated_by'] = $user->id;
        $input['history_updated'] = savingHistory($user, $datawilayah->history_updated);

        $datawilayah = $this->datawilayahRepository->update($input, $id);

        Flash::success('Informasi Daerah/Tahun updated successfully.');
        return redirect(route('datawilayahs.show', $input['id_wilayah']));
    }

    /**
     * Remove the specified Datawilayah from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $datawilayah = $this->datawilayahRepository->findWithoutFail($id);

        if (empty($datawilayah)) {
            Flash::error('Informasi Daerah/Tahun not found');
            return redirect(route('datawilayahs.index'));
        }

        $updated = [
            'history_updated' => savingHistory($user, $datawilayah->history_updated, true)
        ];

        $this->datawilayahRepository->update($updated, $id);

        $this->datawilayahRepository->delete($id);

        Flash::success('Informasi Daerah/Tahun deleted successfully.');
        return redirect(route('datawilayahs.index'));
    }



    /**
     * Store data Datawilayah from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function ($reader) {
            $reader->each(function ($item) {
                $datawilayah = $this->datawilayahRepository->create($item->toArray());
            });
        });

        Flash::success('Informasi Daerah/Tahun saved successfully.');
        return redirect(route('datawilayahs.index'));
    }
}
