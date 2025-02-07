<?php

namespace App\Http\Controllers;

use App\DataTables\BahasaDataTable;
use App\Http\Requests\CreateBahasaRequest;
use App\Http\Requests\UpdateBahasaRequest;
use App\Repositories\BahasaRepository;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use App\Services\UploadFileService;
use Response;
use Illuminate\Http\Request; 
use Maatwebsite\Excel\Facades\Excel; 

class BahasaController extends AppBaseController
{
    /** @var  BahasaRepository */
    private $bahasaRepository;

    /**@var UploadFileService */
    private $uploadFileService;

    private $path = 'flag/';

    public function __construct(BahasaRepository $bahasaRepo, UploadFileService $uploadFileService)
    {
        $this->middleware('auth');
        $this->middleware('can:appStructure-edit', ['only' => ['edit']]);
        $this->middleware('can:appStructure-store', ['only' => ['store']]);
        $this->middleware('can:appStructure-show', ['only' => ['show']]);
        $this->middleware('can:appStructure-update', ['only' => ['update']]);
        $this->middleware('can:appStructure-delete', ['only' => ['delete']]);
        $this->middleware('can:appStructure-create', ['only' => ['create']]);
        $this->bahasaRepository = $bahasaRepo;
        $this->uploadFileService = $uploadFileService;
    }

    /**
     * Display a listing of the Bahasa.
     *
     * @param BahasaDataTable $bahasaDataTable
     * @return Response
     */
    public function index(BahasaDataTable $bahasaDataTable)
    {
        return $bahasaDataTable->render('bahasas.index');
    }

    /**
     * Show the form for creating a new Bahasa.
     *
     * @return Response
     */
    public function create()
    {
       return view('bahasas.create');
    }

    /**
     * Store a newly created Bahasa in storage.
     *
     * @param CreateBahasaRequest $request
     *
     * @return Response
     */
    public function store(CreateBahasaRequest $request)
    {
        $input = $request->all();

        if (@$request->flag){
            $input['flag'] = $this->uploadFileService->uploadFile($this->path, 'flag', @$request->flag);
        }

        $bahasa = $this->bahasaRepository->create($input);

        Flash::success('Bahasa saved successfully.');
        return redirect(route('bahasas.index'));
    }

    /**
     * Display the specified Bahasa.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $bahasa = $this->bahasaRepository->findWithoutFail($id);

        if (empty($bahasa)) {
            Flash::error('Bahasa not found');
            return redirect(route('bahasas.index'));
        }

        return view('bahasas.show')->with('bahasa', $bahasa);
    }

    /**
     * Show the form for editing the specified Bahasa.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        
        $bahasa = $this->bahasaRepository->findWithoutFail($id);

        if (empty($bahasa)) {
            Flash::error('Bahasa not found');
            return redirect(route('bahasas.index'));
        }

        return view('bahasas.edit', ['bahasa' => $bahasa]);

    }

    /**
     * Update the specified Bahasa in storage.
     *
     * @param  int              $id
     * @param UpdateBahasaRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $bahasa = $this->bahasaRepository->findWithoutFail($id);

        if (empty($bahasa)) {
            Flash::error('Bahasa not found');
            return redirect(route('bahasas.index'));
        }

        $input = $request->all();

        if (@$request->flag){
            $input['flag'] = $this->uploadFileService->uploadFile($this->path, 'flag', @$request->flag, $bahasa, 'update');
        } else {
            $input['flag'] = $bahasa->flag;
        }

        $bahasa = $this->bahasaRepository->update($input, $id);

        Flash::success('Bahasa updated successfully.');
        return redirect(route('bahasas.index'));
    }

    /**
     * Remove the specified Bahasa from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $bahasa = $this->bahasaRepository->findWithoutFail($id);

        if (empty($bahasa)) {
            Flash::error('Bahasa not found');
            return redirect(route('bahasas.index'));
        }

        $this->bahasaRepository->delete($id);

        Flash::success('Bahasa deleted successfully.');
        return redirect(route('bahasas.index'));
    }

    /**
     * Store data Bahasa from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $bahasa = $this->bahasaRepository->create($item->toArray());
            });
        });

        Flash::success('Bahasa saved successfully.');
        return redirect(route('bahasas.index'));
    }
}
