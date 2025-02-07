<?php

namespace App\Http\Controllers;

use App\DataTables\SumberdataDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateSumberdataRequest;
use App\Http\Requests\UpdateSumberdataRequest;
use App\Repositories\SumberdataRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BahasaRepository;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel; 

class SumberdataController extends AppBaseController
{
    /** @var  SumberdataRepository */
    private $sumberdataRepository;
    private $bahasa;

    public function __construct(SumberdataRepository $sumberdataRepo, BahasaRepository $bahasaRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:sumberdata-edit', ['only' => ['edit']]);
        $this->middleware('can:sumberdata-store', ['only' => ['store']]);
        $this->middleware('can:sumberdata-show', ['only' => ['show']]);
        $this->middleware('can:sumberdata-update', ['only' => ['update']]);
        $this->middleware('can:sumberdata-delete', ['only' => ['delete']]);
        $this->middleware('can:sumberdata-create', ['only' => ['create']]);
        $this->sumberdataRepository = $sumberdataRepo;
        $this->bahasa = $bahasaRepo;
    }

    /**
     * Display a listing of the Sumberdata.
     *
     * @param SumberdataDataTable $sumberdataDataTable
     * @return Response
     */
    public function index(SumberdataDataTable $sumberdataDataTable)
    {
        $akses = Auth::user();
        if(!$akses->can('sumberdata-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return $sumberdataDataTable->render('sumberdatas.index');
    }

    /**
     * Show the form for creating a new Sumberdata.
     *
     * @return Response
     */
    public function create()
    {
        $akses = Auth::user();
        if(!$akses->can('sumberdata-create')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $bahasa = $this->bahasa->all();
        return view('sumberdatas.create')->with('bahasa', $bahasa);
    }

    /**
     * Store a newly created Sumberdata in storage.
     *
     * @param CreateSumberdataRequest $request
     *
     * @return Response
     */
    public function store(CreateSumberdataRequest $request)
    {
        $input = $request->all();

        $sumberdata = $this->sumberdataRepository->create($input);

        Flash::success('Sumber Data Sektor/Bidang saved successfully.');
        return redirect(route('sumberdatas.index'));
    }

    /**
     * Display the specified Sumberdata.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $akses = Auth::user();
        if(!$akses->can('sumberdata-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $sumberdata = $this->sumberdataRepository->findWithoutFail($id);

        if (empty($sumberdata)) {
            Flash::error('Sumber Data Sektor/Bidang not found');
            return redirect(route('sumberdatas.index'));
        }

        return view('sumberdatas.show')->with('sumberdata', $sumberdata);
    }

    /**
     * Show the form for editing the specified Sumberdata.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $akses = Auth::user();
        if(!$akses->can('sumberdata-edit')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $sumberdata = $this->sumberdataRepository->findWithoutFail($id);

        if (empty($sumberdata)) {
            Flash::error('Sumber Data Sektor/Bidang not found');
            return redirect(route('sumberdatas.index'));
        }

        $bahasa = $this->bahasa->all();
        return view('sumberdatas.edit')->with('bahasa', $bahasa)
        ->with('sumberdata', $sumberdata);
    }

    /**
     * Update the specified Sumberdata in storage.
     *
     * @param  int              $id
     * @param UpdateSumberdataRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSumberdataRequest $request)
    {
        $sumberdata = $this->sumberdataRepository->findWithoutFail($id);

        if (empty($sumberdata)) {
            Flash::error('Sumber Data Sektor/Bidang not found');
            return redirect(route('sumberdatas.index'));
        }

        $input = $request->all();
        $sumberdata = $this->sumberdataRepository->update($input, $id);

        Flash::success('Sumber Data Sektor/Bidang updated successfully.');
        return redirect(route('sumberdatas.index'));
    }

    /**
     * Remove the specified Sumberdata from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $sumberdata = $this->sumberdataRepository->findWithoutFail($id);

        if (empty($sumberdata)) {
            Flash::error('Sumber Data Sektor/Bidang not found');
            return redirect(route('sumberdatas.index'));
        }

        $this->sumberdataRepository->delete($id);

        Flash::success('Sumber Data Sektor/Bidang deleted successfully.');
        return redirect(route('sumberdatas.index'));
    }

    /**
     * Store data Sumberdata from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $sumberdata = $this->sumberdataRepository->create($item->toArray());
            });
        });

        Flash::success('Sumber Data Sektor/Bidang saved successfully.');
        return redirect(route('sumberdatas.index'));
    }
}
