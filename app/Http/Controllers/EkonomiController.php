<?php

namespace App\Http\Controllers;

use App\DataTables\EkonomiDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateEkonomiRequest;
use App\Http\Requests\UpdateEkonomiRequest;
use App\Repositories\EkonomiRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BahasaRepository;
use App\Services\SaveFileService;
use App\Services\UploadFileService;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class EkonomiController extends AppBaseController
{
    /** @var  EkonomiRepository */
    private $ekonomiRepository;
    private $saveFile;
    private $bahasaRepopsitory;
    private $uploadFile;
    private $path = 'ekonomi-daerah/';

    public function __construct(EkonomiRepository $ekonomiRepo, BahasaRepository $bahasaRepo, SaveFileService $saveFileService)
    {
        $this->middleware('auth');
        $this->middleware('can:ekonomi-edit', ['only' => ['edit']]);
        $this->middleware('can:ekonomi-store', ['only' => ['store']]);
        $this->middleware('can:ekonomi-show', ['only' => ['show']]);
        $this->middleware('can:ekonomi-update', ['only' => ['update']]);
        $this->middleware('can:ekonomi-delete', ['only' => ['delete']]);
        $this->middleware('can:ekonomi-create', ['only' => ['create']]);
        $this->ekonomiRepository = $ekonomiRepo;
        $this->saveFile = $saveFileService;
        $this->bahasaRepopsitory = $bahasaRepo;
        $this->uploadFile = new UploadFileService();
    }

    /**
     * Display a listing of the Ekonomi.
     *
     * @param EkonomiDataTable $ekonomiDataTable
     * @return Response
     */
    public function index(EkonomiDataTable $ekonomiDataTable)
    {
        $akses = Auth::user();
        if(!$akses->can('ekonomi-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return $ekonomiDataTable->render('ekonomis.index');
    }

    /**
     * Show the form for creating a new Ekonomi.
     *
     * @return Response
     */
    public function create()
    {
        $akses = Auth::user();
        if(!$akses->can('ekonomi-create')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $bahasa = $this->bahasaRepopsitory->all();
        return view('ekonomis.create')->with('bahasa', $bahasa);
    }

    /**
     * Store a newly created Ekonomi in storage.
     *
     * @param CreateEkonomiRequest $request
     *
     * @return Response
     */
    public function store(CreateEkonomiRequest $request)
    {
        $input = $request->all();

        //create code uniq
        $last_id = $this->ekonomiRepository->max('id');
        $input['kode'] = intval(@$last_id) + 1;

        if (@$request->icon_light_mode) {
            // $input['icon_light_mode'] = $this->saveFile->setImage(@$request->icon_light_mode)->setStorage('ekonomi-daerah')->handle();
            $input['icon_light_mode'] = $this->uploadFile->uploadFile($this->path,'ekonomi-daerah',@$request->icon_light_mode);
        } else {
            $input['icon_light_mode'] = null;
        }

        if (@$request->icon_dark_mode) {
            // $input['icon_dark_mode'] = $this->saveFile->setImage(@$request->icon_dark_mode)->setStorage('ekonomi-daerah')->handle();
            $input['icon_dark_mode'] = $this->uploadFile->uploadFile($this->path,'ekonomi-daerah',@$request->icon_dark_mode);
        } else {
            $input['icon_dark_mode'] = null;
        }

        $this->ekonomiRepository->create($input);

        Flash::success('Ekonomi saved successfully.');
        return redirect(route('ekonomis.index'));
    }

    /**
     * Display the specified Ekonomi.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $akses = Auth::user();
        if(!$akses->can('ekonomi-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $ekonomi = $this->ekonomiRepository->findWithoutFail($id);

        if (empty($ekonomi)) {
            Flash::error('Ekonomi not found');
            return redirect(route('ekonomis.index'));
        }

        return view('ekonomis.show')->with('ekonomi', $ekonomi);
    }

    /**
     * Show the form for editing the specified Ekonomi.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $akses = Auth::user();
        if(!$akses->can('ekonomi-edit')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $ekonomi = $this->ekonomiRepository->findWithoutFail($id);

        if (empty($ekonomi)) {
            Flash::error('Ekonomi not found');
            return redirect(route('ekonomis.index'));
        }

        $bahasa = $this->bahasaRepopsitory->all();
        return view('ekonomis.edit')
            ->with('ekonomi', $ekonomi)->with('bahasa', $bahasa);
    }

    /**
     * Update the specified Ekonomi in storage.
     *
     * @param  int              $id
     * @param UpdateEkonomiRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEkonomiRequest $request)
    {
        $ekonomi = $this->ekonomiRepository->findWithoutFail($id);

        if (empty($ekonomi)) {
            Flash::error('Ekonomi not found');
            return redirect(route('ekonomis.index'));
        }

        $input = $request->all();

        if (empty($ekonomi->kode)) {
            $input['kode'] = $ekonomi->id;
        }

        if (@$request->icon_light_mode) {
            // $input['icon_light_mode'] = $this->saveFile->setImage(@$request->icon_light_mode)->setStorage('ekonomi-daerah')->handle();
            $input['icon_light_mode'] = $this->uploadFile->uploadFile($this->path,'ekonomi-daerah',@$request->icon_light_mode, $ekonomi, 'update');
        } else {
            $input['icon_light_mode'] = @$ekonomi->icon_light_mode;
        }

        if (@$request->icon_dark_mode) {
            // $input['icon_dark_mode'] = $this->saveFile->setImage(@$request->icon_dark_mode)->setStorage('ekonomi-daerah')->handle();
            $input['icon_dark_mode'] = $this->uploadFile->uploadFile($this->path,'ekonomi-daerah',@$request->icon_dark_mode, $ekonomi, 'update');
        } else {
            $input['icon_dark_mode'] = @$ekonomi->icon_dark_mode;
        }

        $ekonomi = $this->ekonomiRepository->update($input, $id);

        Flash::success('Ekonomi updated successfully.');
        return redirect(route('ekonomis.index'));
    }

    /**
     * Remove the specified Ekonomi from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $ekonomi = $this->ekonomiRepository->findWithoutFail($id);

        if (empty($ekonomi)) {
            Flash::error('Ekonomi not found');
            return redirect(route('ekonomis.index'));
        }

        $this->ekonomiRepository->delete($id);
        @$this->saveFile->setModel(@$ekonomi->icon)->setStorage('ekonomi-daerah')->isDelete(1)->handle();

        Flash::success('Ekonomi deleted successfully.');
        return redirect(route('ekonomis.index'));
    }

    /**
     * Store data Ekonomi from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function ($reader) {
            $reader->each(function ($item) {
                $ekonomi = $this->ekonomiRepository->create($item->toArray());
            });
        });

        Flash::success('Ekonomi saved successfully.');
        return redirect(route('ekonomis.index'));
    }
}
