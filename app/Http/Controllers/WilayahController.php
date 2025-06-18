<?php

namespace App\Http\Controllers;

use App\DataTables\Wilayah1DataTable;
use App\DataTables\WilayahDataTable;
use App\Export\WilayahExport;
use App\Http\Requests;
use App\Http\Requests\CreateWilayahRequest;
use App\Http\Requests\UpdateWilayahRequest;
use App\Repositories\WilayahRepository;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use App\Import\WilayahImport;
use App\Models\Dataran;
use App\Models\Wilayah;
use App\Repositories\DataranRepository;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\SaveFileService;
use App\Services\UploadFileService;
use App\User;
use App\Repositories\BahasaRepository;

class WilayahController extends AppBaseController
{
    /** @var  WilayahRepository */
    private $wilayahRepository;

    /** @var  DataranRepository */
    private $dataranRepository;


    /** @var  SaveFileService */
    private $saveFile;

    /** @var  BahasaRepository */
    private $bahasaRepopsitory;

    private $uploadFile;
    private $path = 'daerah/';

    public function __construct(WilayahRepository $wilayahRepo, DataranRepository $dataranRepo, SaveFileService $saveFileService, BahasaRepository $bahasaRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:wilayah-edit', ['only' => ['edit']]);
        $this->middleware('can:wilayah-store', ['only' => ['store']]);
        $this->middleware('can:wilayah-show', ['only' => ['show']]);
        $this->middleware('can:wilayah-update', ['only' => ['update']]);
        $this->middleware('can:wilayah-delete', ['only' => ['delete']]);
        $this->middleware('can:wilayah-create', ['only' => ['create']]);
        $this->wilayahRepository = $wilayahRepo;
        $this->saveFile = $saveFileService;
        $this->dataranRepository = $dataranRepo;
        $this->uploadFile = new UploadFileService();
        $this->bahasaRepopsitory = $bahasaRepo;
    }

    /**
     * Display a listing of available Provinces.
     *
     * @param WilayahDataTable $wilayahDataTable
     * @return Response
     */
    public function index(WilayahDataTable $wilayahDataTable) // DataTable di inject pada fungsi
    {
        /** @var User */
        $akses = Auth::user();
        if (!$akses->can('wilayah-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return $wilayahDataTable->render('wilayahs.index'); // render view dengan memasukan nama view pada fungsi render
    }

    /**
     * Display listing of available Cities
     * @param \App\DataTables\Wilayah1DataTable $wilayah1DataTable
     * @param mixed $id
     * @return mixed
     */
    public function indexChild(Wilayah1DataTable $wilayah1DataTable, $id)
    {

        /** @var User  */
        $akses = Auth::user();
        if (!$akses->can('wilayah-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $wilayah = $this->wilayahRepository->findWithoutFail($id);

        if (empty($wilayah)) {
            Flash::error('Provinsi not found');
            return redirect(route('Wilayahs.index'));
        }

        return $wilayah1DataTable->setWilayah($id)->renders('wilayahs.index_child', ['wilayah' => $wilayah]);
    }

    /**
     * Show the form for creating a new Wilayah.
     *
     * @return Response
     */
    public function create()
    {
        /** @var User $akses  */
        $akses = Auth::user();
        if (!$akses->can('wilayah-create')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $datarans = Dataran::all();
        $lang = $this->bahasaRepopsitory->all();

        if ($datarans->count() > 0) $dataran = $datarans->pluck('nama', 'id');
        else $dataran = ['' => 'Pilih Dataran'];
        $max = $this->wilayahRepository->whereNull('id_parent')->max('id_increament');

        return view('wilayahs.create')->with('dataran', $dataran)->with('max_code', $max)
            ->with('lang', $lang);
    }

    /**
     * Show the form for creating a new Wilayah.
     *
     * @return Response
     */
    public function createChild($provinceId)
    {
        /** @var User $akses  */
        $akses = Auth::user();
        if (!$akses->can('wilayah-create')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $province = $this->wilayahRepository->findWithoutFail($provinceId);
        if (empty($province)) {
            Flash::error('Provinsi not found');
            return redirect(route('Wilayahs.index'));
        }

        $datarans = Dataran::all();
        $lang = $this->bahasaRepopsitory->all();
        if ($datarans->count() > 0) $dataran = $datarans->pluck('nama', 'id');
        else $dataran = ['' => 'Pilih Dataran'];

        $max = $this->wilayahRepository->where('id_parent', $provinceId)->max('id_increament');
        return view('wilayahs.create_child')->with('dataran', $dataran)->with('parent', $province)->with('max_code', intval(@$max) + 1)
            ->with('lang', $lang);
    }

    /**
     * Store a newly created Wilayah in storage.
     *
     * @param CreateWilayahRequest $request
     *
     * @return Response
     */
    public function store(CreateWilayahRequest $request)
    {
        $input = $request->all();
        $province = null;

        if (intval(@$input["parent_id"]) > 0) {
            $province = $this->wilayahRepository->findWithoutFail(@$input["parent_id"]);
            if (empty($province)) {
                Flash::error('Provinsi not found');
                return redirect(route('Wilayahs.index'));
            }
        }

        if (@$request->peta_light_mode) {
            // $input['peta_light_mode'] = $this->saveFile->setImage(@$request->peta_light_mode)->setStorage('pemda')->handle();
            $input['peta_light_mode'] = $this->uploadFile->uploadFile($this->path, 'daerah', @$request->peta_light_mode);
        } else {
            $input['peta_light_mode'] = null;
        }

        if (@$request->peta_dark_mode) {
            // $input['peta_dark_mode'] = $this->saveFile->setImage(@$request->peta_dark_mode)->setStorage('pemda')->handle();
            $input['peta_dark_mode'] = $this->uploadFile->uploadFile($this->path, 'daerah', @$request->peta_dark_mode);
        } else {
            $input['peta_dark_mode'] = null;
        }

        $input['has_data'] = @$request->has_data ? @$request->has_data : 0;
        $input["id_parent"] = @$input["parent_id"];

        //kode
        if (empty($province)) {
            $input["kode"] = @$input["id_increament"] . '00';
            $input["has_data"] = intval(@$input["has_data"]);
        } else {
            $input["kode"] = @$province->id_increament . '.' . str_pad(@@$input["id_increament"], 2, '0', STR_PAD_LEFT);
        }

        $wilayah = $this->wilayahRepository->create($input);

        Flash::success('Wilayah saved successfully.');
        if (intval(@$input["parent_id"]) > 0) {
            return redirect(route('wilayahs.cities', @$input["parent_id"]));
        } else {
            return redirect(route('wilayahs.index'));
        }
    }

    /**
     * Display the specified Wilayah.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var User $akses  */
        $akses = Auth::user();
        if (!$akses->can('wilayah-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        // $wilayah = $this->wilayahRepository->with('cities')->whereNull('id_parent')->orderBy('id','asc')->get();
        // $parent = 0;
        // foreach($wilayah as $wil){
        //     if($wil->tipe == 'propinsi'){
        //         echo $wil->id . ' :: null :: ' . $wil->kode . ' :: ' . $wil->tipe . ' :: ' . $wil->nama .'<br />';
        //         $parent = $wil->id;
        //     }
        //     else{
        //         echo $wil->id . ' :: ' . $parent . ' :: ' . $wil->kode . ' :: ' . $wil->tipe . ' :: ' . $wil->nama .'<br />';
        //         $this->wilayahRepository->update(['id_parent' => $parent], $wil->id);
        //     }
        // }

        // $wilayah = $this->wilayahRepository->with('cities')->whereNull('id_parent')->orderBy('nama','asc')->get();
        // foreach($wilayah as $wil){

        //     $parent = $this->wilayahRepository->findWithoutFail($wil->id_parent);
        //     $kode = 1;
        //     if(!empty($parent)){
        //         $kode = $wil->kode . '.' . $wil->id_increament;
        //     }
        //     else{
        //         $kode = $wil->id_increament;
        //     }

        //     echo $wil->id_increament . ' :: ' . $kode . ' :: ' . $wil->nama .'<br />';
        //     if(count($wil->cities))
        //     {
        //         $this->get_child_names($wil->cities, $wil->id_increament);
        //     }
        // }
        // $wilayah = $this->wilayahRepository->findWithoutFail($id);

        // if (empty($wilayah)) {
        //     Flash::error('Wilayah not found');
        //     return redirect(route('wilayahs.index'));
        // }

        return view('wilayahs.show');
    }

    function get_child_names($data, $inc)
    {
        $num = 1;
        foreach ($data as $wil) {
            $kode = $inc . '.' . $num;
            echo $num . ' :: ' . $kode . ' :: ' . $wil->nama . '<br />';

            Wilayah::whereId($wil->id)->update(['id_increament' => $num, 'kode' => $kode]);
            if (count($wil->cities)) {
                $this->get_child_names($wil->cities, $num);
            }
            $num++;
        }
    }

    /**
     * Show the form for editing the specified Wilayah.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var User $akses  */
        $akses = Auth::user();
        if (!$akses->can('wilayah-edit')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $wilayah = $this->wilayahRepository->findWithoutFail($id);

        if (empty($wilayah)) {
            Flash::error('Wilayah not found');
            return redirect(route('wilayahs.index'));
        }

        $datarans = Dataran::all();
        $lang = $this->bahasaRepopsitory->all();
        if ($datarans->count() > 0) $dataran = $datarans->pluck('nama', 'id');
        else $dataran = ['' => 'Pilih Dataran'];

        if (!empty($wilayah->id_parent)) {
            $parent_data = $this->wilayahRepository->findWithoutFail($wilayah->id_parent);
        } else {
            $parent_data = null;
        }

        return view('wilayahs.edit')
            ->with('wilayah', $wilayah)->with('dataran', $dataran)->with('parent', $parent_data)
            ->with('lang', $lang);
    }

    /**
     * Show the form for editing the specified Wilayah.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function editChild($id)
    {
        /** @var User $akses  */
        $akses = Auth::user();
        if (!$akses->can('wilayah-edit')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $wilayah = $this->wilayahRepository->findWithoutFail($id);

        if (empty($wilayah)) {
            Flash::error('Kabupaten/Kota not found');
            return redirect(route('wilayahs.index'));
        }

        $province = $this->wilayahRepository->findWithoutFail($wilayah->id_parent);

        if (empty($province)) {
            Flash::error('Provinsi not found');
            return redirect(route('wilayahs.index'));
        }

        $datarans = Dataran::all();
        $lang = $this->bahasaRepopsitory->all();
        if ($datarans->count() > 0) $dataran = $datarans->pluck('nama', 'id');
        else $dataran = ['' => 'Pilih Dataran'];

        return view('wilayahs.edit_child')
            ->with('wilayah', $wilayah)->with('dataran', $dataran)->with('province', $province)
            ->with('lang', $lang);
    }

    /**
     * Update the specified Wilayah in storage.
     *
     * @param  int              $id
     * @param UpdateWilayahRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWilayahRequest $request)
    {
        $input = $request->all();
        $province = null;
        $wilayah = $this->wilayahRepository->findWithoutFail($id);

        if (empty($wilayah)) {
            Flash::error('Provinsi/Kab/Kota not found');
            return redirect(route('wilayahs.index'));
        }

        if (intval(@$input["parent_id"]) > 0) {
            $province = $this->wilayahRepository->findWithoutFail(@$input["parent_id"]);

            if (empty($province)) {
                Flash::error('Provinsi not found');
                return redirect(route('wilayahs.index'));
            }
        }

        //cek kode
        if (!empty($province)) {
            $cek = $this->wilayahRepository->where('id_parent', $province->id)->where('id_increament', @$input["id_increament"])->where('id', '!=', $id)->first();
            @$input['kode'] = $province->id_increament . '.' . str_pad(@$input["id_increament"], 2, '0', STR_PAD_LEFT);
        } else {
            $cek = $this->wilayahRepository->whereNull('id_parent')->where('id_increament', @$input["id_increament"])->where('id', '!=', $id)->first();
            @$input['kode'] = @$input["id_increament"] . '.00';
            $input["has_data"] = intval(@$input["has_data"]);
        }

        if (!empty($cek)) {
            return redirect()->back()->withInput($input)->withErrors($cek . ' Kode sudah di gunakan, silakan gunakan kode lain');
        }

        if ($request->hasFile('peta_light_mode')) {
            $input['peta_light_mode'] = $this->uploadFile->uploadFile($this->path, 'daerah', @$request->peta_light_mode, $wilayah, 'update');
        } elseif ($wilayah->peta_light_mode && $request->get('peta_light_mode') === null) {
            $input['peta_light_mode'] = null;
        } else {
            $input['peta_light_mode'] = @$wilayah->peta_light_mode;
        }

        if ($request->hasFile('peta_dark_mode')) {
            $input['peta_dark_mode'] = $this->uploadFile->uploadFile($this->path, 'daerah', @$request->peta_dark_mode, $wilayah, 'update');
        } elseif ($wilayah->peta_dark_mode && $request->get('peta_dark_mode') === null) {
            $input['peta_dark_mode'] = null;
        } else {
            $input['peta_dark_mode'] = @$wilayah->peta_dark_mode;
        }

        // dd($request);

        $input['has_data'] = @$request->has_data ? @$request->has_data : 0;

        $wilayah = $this->wilayahRepository->update($input, $id);

        if (!empty($province)) {
            Flash::success('Kab/Kota updated successfully.');
            return redirect(route('wilayahs.cities', @$province->id));
        } else {
            Flash::success('Provinsi updated successfully.');
            return redirect(route('wilayahs.index'));
        }
    }

    /**
     * Remove the specified Wilayah from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $wilayah = $this->wilayahRepository->findWithoutFail($id);
        $province = null;

        if (empty($wilayah)) {
            Flash::error('Provinsi/Kab/Kota not found');
            return redirect(route('wilayahs.index'));
        }

        if (!empty(@$wilayah->id_parent)) {

            $province = $this->wilayahRepository->findWithoutFail(@$wilayah->id_parent);

            if (empty($province)) {
                Flash::error('Provinsi not found');
                return redirect(route('wilayahs.index'));
            }
        }

        $this->wilayahRepository->delete($id);

        if (!empty($province)) {
            Flash::success('Kab/Kota deleted successfully.');
            return redirect(route('wilayahs.cities', @$province->id));
        } else {
            Flash::success('Provinsi deleted successfully.');
            return redirect(route('wilayahs.index'));
        }
    }

    /**
     * Store data Wilayah from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function ($reader) {
            $reader->each(function ($item) {
                $wilayah = $this->wilayahRepository->create($item->toArray());
            });
        });

        Flash::success('Wilayah saved successfully.');
        return redirect(route('wilayahs.index'));
    }

    public function download_template()
    {
        $masterWilayah = $this->wilayahRepository->select('id', 'kode', 'nama')->get();
        $dataran = $this->dataranRepository->getArrayTableDataran();

        $data = [
            'wilayah' => $masterWilayah,
            'dataran' => $dataran
        ];

        return Excel::download(new WilayahExport($data), 'Template_Master_Wilayah.xlsx');
    }

    public function uploadExcel(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $tahun = $request->tahun;

            $import = new WilayahImport;
            Excel::import($import, $file);
            Flash::success('Master Prov/Kab/Kota successfully uploaded');
            return redirect(route('datawilayahs.index'));
        } else {
            Flash::error('No File Uploaded');
            return redirect(route('datawilayahs.create'));
        }
    }

    public function ajax(Request $request)
    {
        $term = $request->term;
        $wilayah = $this->wilayahRepository->with('province')->where('tipe', '!=', 'propinsi')->where('nama', 'like', '%' . $term . '%')->get();
        $items = [];
        if ($wilayah->count() > 0) {
            foreach ($wilayah as  $wil) {
                $items[] = [
                    'id' => $wil->id,
                    'nama' => $wil->nama,
                    'propinsi' => $wil->province->nama
                ];
            }
        }
        return response()->json(['items' => $items], 200);
    }
}
