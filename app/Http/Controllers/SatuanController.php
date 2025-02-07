<?php

namespace App\Http\Controllers;

use App\DataTables\SatuanDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateSatuanRequest;
use App\Http\Requests\UpdateSatuanRequest;
use App\Repositories\SatuanRepository;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BahasaRepository;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel; 
use App\Models\User;

class SatuanController extends AppBaseController
{
    /** @var  SatuanRepository */
    private $satuanRepository;

    /** @var BahasaRepository */
    private $languageRepository;

    public function __construct(SatuanRepository $satuanRepo, BahasaRepository $languageRepository)
    {
        $this->middleware('auth');
        $this->middleware('can:satuan-edit', ['only' => ['edit']]);
        $this->middleware('can:satuan-store', ['only' => ['store']]);
        $this->middleware('can:satuan-show', ['only' => ['show']]);
        $this->middleware('can:satuan-update', ['only' => ['update']]);
        $this->middleware('can:satuan-delete', ['only' => ['delete']]);
        $this->middleware('can:satuan-create', ['only' => ['create']]);
        $this->satuanRepository = $satuanRepo;
        $this->languageRepository = $languageRepository;
    }

    /**
     * Display a listing of the Satuan.
     *
     * @param SatuanDataTable $satuanDataTable
     * @return Response
     */
    public function index(SatuanDataTable $satuanDataTable)
    {
        /** @var User $akses */
        $akses = Auth::user();
        if(!$akses->can('satuan-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return $satuanDataTable->render('satuans.index');
    }

    /**
     * Show the form for creating a new Satuan.
     *
     * @return Response
     */
    public function create()
    {
        /** @var User $akses  */
        $akses = Auth::user();
        if(!$akses->can('satuan-create')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        $lang = $this->languageRepository->all();


        return view('satuans.create')
        ->with('lang', $lang);
    }

    /**
     * Store a newly created Satuan in storage.
     *
     * @param CreateSatuanRequest $request
     *
     * @return Response
     */
    public function store(CreateSatuanRequest $request)
    {
        $input = $request->all();

        $satuan = $this->satuanRepository->create($input);

        Flash::success('Satuan Data saved successfully.');
        return redirect(route('satuans.index'));
    }

    /**
     * Display the specified Satuan.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var User $akses  */    
        $akses = Auth::user();
        if(!$akses->can('satuan-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $satuan = $this->satuanRepository->findWithoutFail($id);
        $lang = $this->languageRepository->all();

        if (empty($satuan)) {
            Flash::error('Satuan Data not found');
            return redirect(route('satuans.index'));
        }

        return view('satuans.show')->with('satuan', $satuan)
        ->with('lang', $lang);
    }

    /**
     * Show the form for editing the specified Satuan.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var User $akses  */    
        $akses = Auth::user();
        if(!$akses->can('satuan-edit')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $satuan = $this->satuanRepository->findWithoutFail($id);

        $lang = $this->languageRepository->all();

        if (empty($satuan)) {
            Flash::error('Satuan Data not found');
            return redirect(route('satuans.index'));
        }

        return view('satuans.edit')
            ->with('satuan', $satuan)
            ->with('lang', $lang);
    }

    /**
     * Update the specified Satuan in storage.
     *
     * @param  int              $id
     * @param UpdateSatuanRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSatuanRequest $request)
    {
        $satuan = $this->satuanRepository->findWithoutFail($id);

        if (empty($satuan)) {
            Flash::error('Satuan Data not found');
            return redirect(route('satuans.index'));
        }

        $input = $request->all();
        $satuan = $this->satuanRepository->update($input, $id);

        Flash::success('Satuan Data updated successfully.');
        return redirect(route('satuans.index'));
    }

    /**
     * Remove the specified Satuan from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $satuan = $this->satuanRepository->findWithoutFail($id);

        if (empty($satuan)) {
            Flash::error('Satuan Data not found');
            return redirect(route('satuans.index'));
        }

        $this->satuanRepository->delete($id);

        Flash::success('Satuan Data deleted successfully.');
        return redirect(route('satuans.index'));
    }

    /**
     * Store data Satuan from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $satuan = $this->satuanRepository->create($item->toArray());
            });
        });

        Flash::success('Satuan Data saved successfully.');
        return redirect(route('satuans.index'));
    }
}
