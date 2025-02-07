<?php

namespace App\Http\Controllers;

use App\DataTables\PartaiDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatePartaiRequest;
use App\Http\Requests\UpdatePartaiRequest;
use App\Repositories\PartaiRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Services\SaveFileService;
use App\Services\UploadFileService;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel; 

class PartaiController extends AppBaseController
{
    /** @var  PartaiRepository */
    private $partaiRepository;
    private $saveFile;
    private $path = 'partai/';
    private $uploadFile;


    public function __construct(PartaiRepository $partaiRepo, SaveFileService $saveFileService)
    {
        $this->middleware('auth');
        $this->middleware('can:partai-edit', ['only' => ['edit']]);
        $this->middleware('can:partai-store', ['only' => ['store']]);
        $this->middleware('can:partai-show', ['only' => ['show']]);
        $this->middleware('can:partai-update', ['only' => ['update']]);
        $this->middleware('can:partai-delete', ['only' => ['delete']]);
        $this->middleware('can:partai-create', ['only' => ['create']]);
        $this->partaiRepository = $partaiRepo;
        $this->saveFile = $saveFileService;
        $this->uploadFile = new UploadFileService();

    }

    /**
     * Display a listing of the Partai.
     *
     * @param PartaiDataTable $partaiDataTable
     * @return Response
     */
    public function index(PartaiDataTable $partaiDataTable)
    {
        $akses = Auth::user();
        if(!$akses->can('partai-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return $partaiDataTable->render('partais.index');
    }

    /**
     * Show the form for creating a new Partai.
     *
     * @return Response
     */
    public function create()
    {
        $akses = Auth::user();
        if(!$akses->can('partai-create')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return view('partais.create');
    }

    /**
     * Store a newly created Partai in storage.
     *
     * @param CreatePartaiRequest $request
     *
     * @return Response
     */
    public function store(CreatePartaiRequest $request)
    {
        $input = $request->all();
        if(@$request->logo) {
            $input['logo'] = $this->uploadFile->uploadFile($this->path,'partai',@$request->logo);
            // $this->saveFile->setImage(@$request->logo)->setStorage('partai')->handle();
        }
        else{
            $input['logo'] = null;
        }

        $this->partaiRepository->create($input);

        Flash::success('Partai saved successfully.');
        return redirect(route('partais.index'));
    }

    /**
     * Display the specified Partai.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $akses = Auth::user();
        if(!$akses->can('partai-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $partai = $this->partaiRepository->findWithoutFail($id);

        if (empty($partai)) {
            Flash::error('Partai not found');
            return redirect(route('partais.index'));
        }

        return view('partais.show')->with('partai', $partai);
    }

    /**
     * Show the form for editing the specified Partai.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $akses = Auth::user();
        if(!$akses->can('partai-edit')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $partai = $this->partaiRepository->findWithoutFail($id);

        if (empty($partai)) {
            Flash::error('Partai not found');
            return redirect(route('partais.index'));
        }

        return view('partais.edit')->with('partai', $partai);
    }

    /**
     * Update the specified Partai in storage.
     *
     * @param  int              $id
     * @param UpdatePartaiRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePartaiRequest $request)
    {
        $partai = $this->partaiRepository->findWithoutFail($id);

        if (empty($partai)) {
            Flash::error('Partai not found');
            return redirect(route('partais.index'));
        }

        $input = $request->all();
        
        if(@$request->logo) {            
            $input['logo'] = $this->uploadFile->uploadFile($this->path,'partai',@$request->logo, $partai, 'update');
            // $input['logo'] = $this->saveFile->setModel(@$partai->logo)->setImage(@$request->logo)->setStorage('partai')->isDelete(1)->handle();
        }
        else{
            $input['logo'] = @$partai->logo;
        }
        
        $partai = $this->partaiRepository->update($input, $id);

        Flash::success('Partai updated successfully.');
        return redirect(route('partais.index'));
    }

    /**
     * Remove the specified Partai from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $partai = $this->partaiRepository->findWithoutFail($id);

        if (empty($partai)) {
            Flash::error('Partai not found');
            return redirect(route('partais.index'));
        }

        $this->partaiRepository->delete($id);

        Flash::success('Partai deleted successfully.');
        return redirect(route('partais.index'));
    }

    /**
     * Store data Partai from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $partai = $this->partaiRepository->create($item->toArray());
            });
        });

        Flash::success('Partai saved successfully.');
        return redirect(route('partais.index'));
    }
}
