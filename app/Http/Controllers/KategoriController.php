<?php

namespace App\Http\Controllers;

use App\DataTables\KategoriDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateKategoriRequest;
use App\Http\Requests\UpdateKategoriRequest;
use App\Repositories\KategoriRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BahasaRepository;
use App\Services\SaveFileService;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel; 

class KategoriController extends AppBaseController
{
    /** @var  KategoriRepository */
    private $kategoriRepository;
    private $bahasaRepository;
    private $saveFile;

    public function __construct(KategoriRepository $kategoriRepo, BahasaRepository $bahasaRepo, SaveFileService $saveFileService)
    {
        $this->middleware('auth');
        $this->middleware('can:kategori-edit', ['only' => ['edit']]);
        $this->middleware('can:kategori-store', ['only' => ['store']]);
        $this->middleware('can:kategori-show', ['only' => ['show']]);
        $this->middleware('can:kategori-update', ['only' => ['update']]);
        $this->middleware('can:kategori-delete', ['only' => ['delete']]);
        $this->middleware('can:kategori-create', ['only' => ['create']]);
        $this->kategoriRepository = $kategoriRepo;
        $this->bahasaRepository = $bahasaRepo;
        $this->saveFile = $saveFileService;
    }

    /**
     * Display a listing of the Kategori.
     *
     * @param KategoriDataTable $kategoriDataTable
     * @return Response
     */
    public function index(KategoriDataTable $kategoriDataTable)
    {
        $akses = Auth::user();
        if(!$akses->can('kategori-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return $kategoriDataTable->render('kategoris.index');
    }

    /**
     * Show the form for creating a new Kategori.
     *
     * @return Response
     */
    public function create()
    {
        $akses = Auth::user();
        if(!$akses->can('kategori-create')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return redirect(route('kategoris.index'));
        $bahasa = $this->bahasaRepository->all();
        return view('kategoris.create')->with('bahasa', $bahasa);
    }

    /**
     * Store a newly created Kategori in storage.
     *
     * @param CreateKategoriRequest $request
     *
     * @return Response
     */
    public function store(CreateKategoriRequest $request)
    {
        return redirect(route('kategoris.index'));
        $input = $request->all();

        if(@$request->icon) {
            $input['icon'] = $this->saveFile->setImage(@$request->icon)->setStorage('aktivitas')->handle();
        }
        else{
            $input['icon'] = null;
        }

        $this->kategoriRepository->create($input);

        Flash::success('Kategori saved successfully.');
        return redirect(route('kategoris.index'));
    }

    /**
     * Display the specified Kategori.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $akses = Auth::user();
        if(!$akses->can('kategori-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $kategori = $this->kategoriRepository->findWithoutFail($id);

        if (empty($kategori)) {
            Flash::error('Kategori not found');
            return redirect(route('kategoris.index'));
        }

        return view('kategoris.show')->with('kategori', $kategori);
    }

    /**
     * Show the form for editing the specified Kategori.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $akses = Auth::user();
        if(!$akses->can('kategori-edit')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        $kategori = $this->kategoriRepository->findWithoutFail($id);

        if (empty($kategori)) {
            Flash::error('Kategori not found');
            return redirect(route('kategoris.index'));
        }

        $bahasa = $this->bahasaRepository->all();
        return view('kategoris.edit')
            ->with('kategori', $kategori)->with('bahasa', $bahasa);
    }

    /**
     * Update the specified Kategori in storage.
     *
     * @param  int              $id
     * @param UpdateKategoriRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateKategoriRequest $request)
    {
        $kategori = $this->kategoriRepository->findWithoutFail($id);

        if (empty($kategori)) {
            Flash::error('Kategori not found');
            return redirect(route('kategoris.index'));
        }

        $input = $request->all();

        if(@$request->icon) {
            $input['icon'] = $this->saveFile->setModel(@$kategori->icon)->setImage(@$request->icon)->setStorage('aktivitas')->isDelete(1)->handle();
        }
        else{
            $input['icon'] = @$kategori->icon;
        }
        $kategori = $this->kategoriRepository->update($input, $id);

        Flash::success('Kategori updated successfully.');
        return redirect(route('kategoris.index'));
    }

    /**
     * Remove the specified Kategori from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        return redirect(route('kategoris.index'));
        $kategori = $this->kategoriRepository->findWithoutFail($id);

        if (empty($kategori)) {
            Flash::error('Kategori not found');
            return redirect(route('kategoris.index'));
        }

        $this->kategoriRepository->delete($id);

        Flash::success('Kategori deleted successfully.');
        return redirect(route('kategoris.index'));
    }

    /**
     * Store data Kategori from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $kategori = $this->kategoriRepository->create($item->toArray());
            });
        });

        Flash::success('Kategori saved successfully.');
        return redirect(route('kategoris.index'));
    }
}
