<?php

namespace App\Http\Controllers;

use App\DataTables\RefrenceDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateRefrenceRequest;
use App\Http\Requests\UpdateRefrenceRequest;
use App\Repositories\RefrenceRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BahasaRepository;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class RefrenceController extends AppBaseController
{
    /** @var  RefrenceRepository */
    private $refrenceRepository;
    private $bahasaRepopsitory;

    public function __construct(RefrenceRepository $refrenceRepo, BahasaRepository $bahasaRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:refrence-edit', ['only' => ['edit']]);
        $this->middleware('can:refrence-store', ['only' => ['store']]);
        $this->middleware('can:refrence-show', ['only' => ['show']]);
        $this->middleware('can:refrence-update', ['only' => ['update']]);
        $this->middleware('can:refrence-delete', ['only' => ['delete']]);
        $this->middleware('can:refrence-create', ['only' => ['create']]);
        $this->refrenceRepository = $refrenceRepo;
        $this->bahasaRepopsitory = $bahasaRepo;
    }

    /**
     * Display a listing of the Refrence.
     *
     * @param RefrenceDataTable $refrenceDataTable
     * @return Response
     */
    public function index(RefrenceDataTable $refrenceDataTable)
    {
        $akses = Auth::user();
        if(!$akses->can('refrence-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return $refrenceDataTable->render('refrences.index');
    }

    /**
     * Show the form for creating a new Refrence.
     *
     * @return Response
     */
    public function create()
    {
        $akses = Auth::user();
        if(!$akses->can('refrence-create')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $bahasa = $this->bahasaRepopsitory->all();
        return view('refrences.create')->with('bahasa', $bahasa);
    }

    /**
     * Store a newly created Refrence in storage.
     *
     * @param CreateRefrenceRequest $request
     *
     * @return Response
     */
    public function store(CreateRefrenceRequest $request)
    {
        $input = $request->all();

        $refrence = $this->refrenceRepository->create($input);

        Flash::success('Refrence saved successfully.');
        return redirect(route('refrences.index'));
    }

    /**
     * Display the specified Refrence.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $akses = Auth::user();
        if(!$akses->can('refrence-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $refrence = $this->refrenceRepository->findWithoutFail($id);

        if (empty($refrence)) {
            Flash::error('Refrence not found');
            return redirect(route('refrences.index'));
        }

        return view('refrences.show')->with('refrence', $refrence);
    }

    /**
     * Show the form for editing the specified Refrence.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $akses = Auth::user();
        if(!$akses->can('refrence-edit')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        $refrence = $this->refrenceRepository->findWithoutFail($id);

        if (empty($refrence)) {
            Flash::error('Refrence not found');
            return redirect(route('refrences.index'));
        }

        $bahasa = $this->bahasaRepopsitory->all();

        return view('refrences.edit')
            ->with('refrence', $refrence)
            ->with('bahasa', $bahasa);
    }

    /**
     * Update the specified Refrence in storage.
     *
     * @param  int              $id
     * @param UpdateRefrenceRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRefrenceRequest $request)
    {
        $refrence = $this->refrenceRepository->findWithoutFail($id);

        if (empty($refrence)) {
            Flash::error('Refrence not found');
            return redirect(route('refrences.index'));
        }

        $input = $request->all();
        $refrence = $this->refrenceRepository->update($input, $id);

        Flash::success('Refrence updated successfully.');
        return redirect(route('refrences.index'));
    }

    /**
     * Remove the specified Refrence from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $refrence = $this->refrenceRepository->findWithoutFail($id);

        if (empty($refrence)) {
            Flash::error('Refrence not found');
            return redirect(route('refrences.index'));
        }

        $this->refrenceRepository->delete($id);

        Flash::success('Refrence deleted successfully.');
        return redirect(route('refrences.index'));
    }

    /**
     * Store data Refrence from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $refrence = $this->refrenceRepository->create($item->toArray());
            });
        });

        Flash::success('Refrence saved successfully.');
        return redirect(route('refrences.index'));
    }
}
