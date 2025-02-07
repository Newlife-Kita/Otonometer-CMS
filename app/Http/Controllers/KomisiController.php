<?php

namespace App\Http\Controllers;

use App\DataTables\KomisiDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateKomisiRequest;
use App\Http\Requests\UpdateKomisiRequest;
use App\Repositories\KomisiRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BahasaRepository;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel; 

class KomisiController extends AppBaseController
{
    /** @var  KomisiRepository */
    private $komisiRepository;
    private $bahasaRepository;

    public function __construct(KomisiRepository $komisiRepo, BahasaRepository $bahasaRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:komisi-edit', ['only' => ['edit']]);
        $this->middleware('can:komisi-store', ['only' => ['store']]);
        $this->middleware('can:komisi-show', ['only' => ['show']]);
        $this->middleware('can:komisi-update', ['only' => ['update']]);
        $this->middleware('can:komisi-delete', ['only' => ['delete']]);
        $this->middleware('can:komisi-create', ['only' => ['create']]);
        $this->komisiRepository = $komisiRepo;
        $this->bahasaRepository = $bahasaRepo;
    }

    /**
     * Display a listing of the Komisi.
     *
     * @param KomisiDataTable $komisiDataTable
     * @return Response
     */
    public function index(KomisiDataTable $komisiDataTable)
    {
        $akses = Auth::user();
        if(!$akses->can('komisi-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return $komisiDataTable->render('komisis.index');
    }

    /**
     * Show the form for creating a new Komisi.
     *
     * @return Response
     */
    public function create()
    {
        $akses = Auth::user();
        if(!$akses->can('komisi-create')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $bahasa = $this->bahasaRepository->all();
        return view('komisis.create')->with('bahasa', $bahasa);
    }

    /**
     * Store a newly created Komisi in storage.
     *
     * @param CreateKomisiRequest $request
     *
     * @return Response
     */
    public function store(CreateKomisiRequest $request)
    {
        $input = $request->all();

        $komisi = $this->komisiRepository->create($input);

        Flash::success('Komisi DPRD saved successfully.');
        return redirect(route('komisis.index'));
    }

    /**
     * Display the specified Komisi.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $akses = Auth::user();
        if(!$akses->can('komisi-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $komisi = $this->komisiRepository->findWithoutFail($id);

        if (empty($komisi)) {
            Flash::error('Komisi DPRD not found');
            return redirect(route('komisis.index'));
        }

        return view('komisis.show')->with('komisi', $komisi);
    }

    /**
     * Show the form for editing the specified Komisi.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $akses = Auth::user();
        if(!$akses->can('komisi-edit')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $komisi = $this->komisiRepository->findWithoutFail($id);

        if (empty($komisi)) {
            Flash::error('Komisi DPRD not found');
            return redirect(route('komisis.index'));
        }

        $bahasa = $this->bahasaRepository->all();
        return view('komisis.edit')
        ->with('komisi', $komisi)->with('bahasa', $bahasa);
    }

    /**
     * Update the specified Komisi in storage.
     *
     * @param  int              $id
     * @param UpdateKomisiRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateKomisiRequest $request)
    {
        $komisi = $this->komisiRepository->findWithoutFail($id);

        if (empty($komisi)) {
            Flash::error('Komisi DPRD not found');
            return redirect(route('komisis.index'));
        }

        $input = $request->all();
        $komisi = $this->komisiRepository->update($input, $id);

        Flash::success('Komisi DPRD updated successfully.');
        return redirect(route('komisis.index'));
    }

    /**
     * Remove the specified Komisi from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $komisi = $this->komisiRepository->findWithoutFail($id);

        if (empty($komisi)) {
            Flash::error('Komisi DPRD not found');
            return redirect(route('komisis.index'));
        }

        $this->komisiRepository->delete($id);

        Flash::success('Komisi DPRD deleted successfully.');
        return redirect(route('komisis.index'));
    }

    /**
     * Store data Komisi from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $komisi = $this->komisiRepository->create($item->toArray());
            });
        });

        Flash::success('Komisi DPRD saved successfully.');
        return redirect(route('komisis.index'));
    }
}
