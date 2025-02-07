<?php

namespace App\Http\Controllers;

use App\DataTables\DataranDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateDataranRequest;
use App\Http\Requests\UpdateDataranRequest;
use App\Repositories\DataranRepository;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BahasaRepository;
use App\Services\SaveFileService;
use App\Services\UploadFileService;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class DataranController extends AppBaseController
{
    /** @var  DataranRepository */
    private $dataranRepository;
    private $saveFile;
    private $bahasaRepopsitory;
    private $uploadFile;
    private $path = 'infografis/';

    public function __construct(DataranRepository $dataranRepo, BahasaRepository $bahasaRepo, SaveFileService $saveFileService)
    {
        $this->middleware('auth');
        $this->middleware('can:dataran-edit', ['only' => ['edit']]);
        $this->middleware('can:dataran-store', ['only' => ['store']]);
        $this->middleware('can:dataran-show', ['only' => ['show']]);
        $this->middleware('can:dataran-update', ['only' => ['update']]);
        $this->middleware('can:dataran-delete', ['only' => ['delete']]);
        $this->middleware('can:dataran-create', ['only' => ['create']]);
        $this->dataranRepository = $dataranRepo;
        $this->saveFile = $saveFileService;
        $this->bahasaRepopsitory = $bahasaRepo;
        $this->uploadFile = new UploadFileService();
    }

    /**
     * Display a listing of the Dataran.
     *
     * @param DataranDataTable $dataranDataTable
     * @return Response
     */
    public function index(DataranDataTable $dataranDataTable)
    {
        /** @var User $akses  */
        $akses = Auth::user();
        if(!$akses->can('dataran-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return $dataranDataTable->render('datarans.index');
    }

    /**
     * Show the form for creating a new Dataran.
     *
     * @return Response
     */
    public function create()
    {
        /** @var User $akses  */
        $akses = Auth::user();
        if(!$akses->can('dataran-create')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $bahasa = $this->bahasaRepopsitory->all();
        return view('datarans.create')->with('bahasa', $bahasa);
    }

    /**
     * Store a newly created Dataran in storage.
     *
     * @param CreateDataranRequest $request
     *
     * @return Response
     */
    public function store(CreateDataranRequest $request)
    {
        $input = $request->all();

        //create code uniq
        $last_id = $this->dataranRepository->max('id');
        $input['kode'] = intval(@$last_id) + 1;

        if (@$request->icon_light_mode) {
            // $input['icon_light_mode'] = $this->saveFile->setImage(@$request->icon_light_mode)->setStorage('infografis')->handle();
            $input['icon_light_mode'] = $this->uploadFile->uploadFile($this->path,'infografis',@$request->icon_light_mode);
        } else {
            $input['icon_light_mode'] = null;
        }

        if (@$request->icon_dark_mode) {
            // $input['icon_dark_mode'] = $this->saveFile->setImage(@$request->icon_dark_mode)->setStorage('infografis')->handle();
            $input['icon_dark_mode'] = $this->uploadFile->uploadFile($this->path,'infografis',@$request->icon_dark_mode);
        } else {
            $input['icon_dark_mode'] = null;
        }

        $dataran = $this->dataranRepository->create($input);

        Flash::success('Dataran saved successfully.');
        return redirect(route('datarans.index'));
    }

    /**
     * Display the specified Dataran.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var User $akses  */
        $akses = Auth::user();
        if(!$akses->can('dataran-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $dataran = $this->dataranRepository->findWithoutFail($id);

        if (empty($dataran)) {
            Flash::error('Dataran not found');
            return redirect(route('datarans.index'));
        }

        return view('datarans.show')->with('dataran', $dataran);
    }

    /**
     * Show the form for editing the specified Dataran.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var User $akses  */
        $akses = Auth::user();
        if(!$akses->can('dataran-edit')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $dataran = $this->dataranRepository->findWithoutFail($id);

        if (empty($dataran)) {
            Flash::error('Dataran not found');
            return redirect(route('datarans.index'));
        }

        $bahasa = $this->bahasaRepopsitory->all();

        return view('datarans.edit')
            ->with('dataran', $dataran)
            ->with('bahasa', $bahasa);
    }

    /**
     * Update the specified Dataran in storage.
     *
     * @param  int              $id
     * @param UpdateDataranRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDataranRequest $request)
    {
        $dataran = $this->dataranRepository->findWithoutFail($id);

        if (empty($dataran)) {
            Flash::error('Dataran not found');
            return redirect(route('datarans.index'));
        }

        $input = $request->all();

        if (empty($dataran->kode)) {
            $input['kode'] = $dataran->id;
        }

        if (@$request->icon_light_mode) {
            // $input['icon_light_mode'] = $this->saveFile->setImage(@$request->icon_light_mode)->setStorage('infografis')->handle();
            $input['icon_light_mode'] = $this->uploadFile->uploadFile($this->path,'infografis',@$request->icon_light_mode, $dataran, 'update');
        } else {
            $input['icon_light_mode'] = @$dataran->icon_light_mode;
        }

        if (@$request->icon_dark_mode) {
            // $input['icon_dark_mode'] = $this->saveFile->setImage(@$request->icon_dark_mode)->setStorage('infografis')->handle();
            $input['icon_dark_mode'] = $this->uploadFile->uploadFile($this->path,'infografis',@$request->icon_dark_mode, $dataran, 'update');
        } else {
            $input['icon_dark_mode'] = @$dataran->icon_dark_mode;
        }

        $dataran = $this->dataranRepository->update($input, $id);

        Flash::success('Dataran updated successfully.');
        return redirect(route('datarans.index'));
    }

    /**
     * Remove the specified Dataran from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $dataran = $this->dataranRepository->findWithoutFail($id);

        if (empty($dataran)) {
            Flash::error('Dataran not found');
            return redirect(route('datarans.index'));
        }

        $this->dataranRepository->delete($id);
        @$this->saveFile->setModel(@$dataran->icon)->setStorage('infografis')->isDelete(1)->handle();

        Flash::success('Dataran deleted successfully.');
        return redirect(route('datarans.index'));
    }

    /**
     * Store data Dataran from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function ($reader) {
            $reader->each(function ($item) {
                $dataran = $this->dataranRepository->create($item->toArray());
            });
        });

        Flash::success('Dataran saved successfully.');
        return redirect(route('datarans.index'));
    }
}
