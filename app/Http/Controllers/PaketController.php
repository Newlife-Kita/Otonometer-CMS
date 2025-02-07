<?php

namespace App\Http\Controllers;

use App\DataTables\PaketDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatePaketRequest;
use App\Http\Requests\UpdatePaketRequest;
use App\Repositories\PaketRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BahasaRepository;
use App\Services\SaveFileService;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel; 

class PaketController extends AppBaseController
{
    /** @var  PaketRepository */
    private $paketRepository;
    private $bahasaRepository;
    private $saveFile;

    public function __construct(PaketRepository $paketRepo, BahasaRepository $bahasaRepo,  SaveFileService $saveFileService)
    {
        $this->middleware('auth');
        $this->middleware('can:paket-edit', ['only' => ['edit']]);
        $this->middleware('can:paket-store', ['only' => ['store']]);
        $this->middleware('can:paket-show', ['only' => ['show']]);
        $this->middleware('can:paket-update', ['only' => ['update']]);
        $this->middleware('can:paket-delete', ['only' => ['delete']]);
        $this->middleware('can:paket-create', ['only' => ['create']]);
        $this->paketRepository = $paketRepo;
        $this->bahasaRepository = $bahasaRepo;
        $this->saveFile = $saveFileService;
    }

    /**
     * Display a listing of the Paket.
     *
     * @param PaketDataTable $paketDataTable
     * @return Response
     */
    public function index(PaketDataTable $paketDataTable)
    {
        return $paketDataTable->render('pakets.index');
    }

    /**
     * Show the form for creating a new Paket.
     *
     * @return Response
     */
    public function create()
    {        
        $bahasa = $this->bahasaRepository->all();
        return view('pakets.create')->with('bahasa', $bahasa);
    }

    /**
     * Store a newly created Paket in storage.
     *
     * @param CreatePaketRequest $request
     *
     * @return Response
     */
    public function store(CreatePaketRequest $request)
    {
        $input = $request->all();

        if(@$request->icon) {
            $input['icon'] = $this->saveFile->setImage(@$request->icon)->setStorage('package')->handle();
        }
        else{
            $input['icon'] = null;
        }
        
        if(empty($input['periode'])){
            $input['periode_label'] = NULL;
        }
        
        $paket = $this->paketRepository->create($input);

        Flash::success('Paket saved successfully.');
        return redirect(route('pakets.index'));
    }

    /**
     * Display the specified Paket.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $paket = $this->paketRepository->findWithoutFail($id);

        if (empty($paket)) {
            Flash::error('Paket not found');
            return redirect(route('pakets.index'));
        }

        return view('pakets.show')->with('paket', $paket);
    }

    /**
     * Show the form for editing the specified Paket.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $paket = $this->paketRepository->findWithoutFail($id);

        if (empty($paket)) {
            Flash::error('Paket not found');
            return redirect(route('pakets.index'));
        }
        $bahasa = $this->bahasaRepository->all();
        return view('pakets.edit')
        ->with('paket', $paket)->with('bahasa', $bahasa);
    }

    /**
     * Update the specified Paket in storage.
     *
     * @param  int              $id
     * @param UpdatePaketRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePaketRequest $request)
    {
        $paket = $this->paketRepository->findWithoutFail($id);

        if (empty($paket)) {
            Flash::error('Paket not found');
            return redirect(route('pakets.index'));
        }

        $input = $request->all();
        
        if(@$request->icon) {
            $input['icon'] = $this->saveFile->setModel(@$paket->icon)->setImage(@$request->icon)->setStorage('package')->isDelete(1)->handle();
        }
        else{
            $input['icon'] = $paket->icon;
        }

        if(empty($input['periode'])){
            $input['periode_label'] = NULL;
        }
        
        $paket = $this->paketRepository->update($input, $id);

        Flash::success('Paket updated successfully.');
        return redirect(route('pakets.index'));
    }

    /**
     * Remove the specified Paket from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $paket = $this->paketRepository->findWithoutFail($id);

        if (empty($paket)) {
            Flash::error('Paket not found');
            return redirect(route('pakets.index'));
        }

        $this->paketRepository->delete($id);

        Flash::success('Paket deleted successfully.');
        return redirect(route('pakets.index'));
    }

    /**
     * Store data Paket from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $paket = $this->paketRepository->create($item->toArray());
            });
        });

        Flash::success('Paket saved successfully.');
        return redirect(route('pakets.index'));
    }
}
