<?php

namespace App\Http\Controllers;

use App\DataTables\AnggotaDprdDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateAnggotaDprdRequest;
use App\Http\Requests\UpdateAnggotaDprdRequest;
use App\Repositories\AnggotaDprdRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\KomisiRepository;
use App\Repositories\WilayahRepository;
use App\Services\SaveFileService;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class AnggotaDprdController extends AppBaseController
{
    /** @var  AnggotaDprdRepository */
    private $anggotaDprdRepository;
    private $komisi;
    private $wilayah;
    private $saveFile;

    public function __construct(AnggotaDprdRepository $anggotaDprdRepo, KomisiRepository $komisiRepo, WilayahRepository $wilayahRepo, SaveFileService $saveFileService)
    {
        $this->middleware('auth');
        $this->middleware('can:anggotaDprd-edit', ['only' => ['edit']]);
        $this->middleware('can:anggotaDprd-store', ['only' => ['store']]);
        $this->middleware('can:anggotaDprd-show', ['only' => ['show']]);
        $this->middleware('can:anggotaDprd-update', ['only' => ['update']]);
        $this->middleware('can:anggotaDprd-delete', ['only' => ['delete']]);
        $this->middleware('can:anggotaDprd-create', ['only' => ['create']]);
        $this->anggotaDprdRepository = $anggotaDprdRepo;
        $this->wilayah = $wilayahRepo;
        $this->komisi = $komisiRepo;
        $this->saveFile = $saveFileService;
    }

    /**
     * Display a listing of the AnggotaDprd.
     *
     * @param AnggotaDprdDataTable $anggotaDprdDataTable
     * @return Response
     */
    public function index(AnggotaDprdDataTable $anggotaDprdDataTable)
    {
        $akses = Auth::user();
        if(!$akses->can('anggotaDprd-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return $anggotaDprdDataTable->render('anggota_dprds.index');
    }

    /**
     * Show the form for creating a new AnggotaDprd.
     *
     * @return Response
     */
    public function create()
    {
        $akses = Auth::user();
        if(!$akses->can('anggotaDprd-create')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
       $masterwilayah = $this->wilayah->getArrayWilayah();
       $masterkomisi = $this->komisi->getArrayKomisi();


        return view('anggota_dprds.create')
            ->with('masterwilayah', $masterwilayah)
            ->with('masterkomisi', $masterkomisi);
    }

    /**
     * Store a newly created AnggotaDprd in storage.
     *
     * @param CreateAnggotaDprdRequest $request
     *
     * @return Response
     */
    public function store(CreateAnggotaDprdRequest $request)
    {
        $user = Auth::user();
        $input = $request->all();
        $input['created_by'] = $user->id;
        $input['history_updated'] = savingHistory($user);

        if(@$request->foto) {
            $input['foto'] = $this->saveFile->setImage(@$request->foto)->setStorage('dewan')->handle();
        }
        else{
            $input['foto'] = null;
        }

        $anggotaDprd = $this->anggotaDprdRepository->create($input);

        Flash::success('Anggota Dprd saved successfully.');
        return redirect(route('anggotaDprds.index'));
    }

    /**
     * Display the specified AnggotaDprd.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $akses = Auth::user();
        if(!$akses->can('anggotaDprd-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $anggotaDprd = $this->anggotaDprdRepository->findWithoutFail($id);

        if (empty($anggotaDprd)) {
            Flash::error('Anggota Dprd not found');
            return redirect(route('anggotaDprds.index'));
        }

        return view('anggota_dprds.show')->with('anggotaDprd', $anggotaDprd);
    }

    /**
     * Show the form for editing the specified AnggotaDprd.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $akses = Auth::user();
        if(!$akses->can('anggotaDprd-edit')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        $masterwilayah = $this->wilayah->getArrayWilayah();
        $masterkomisi = $this->komisi->getArrayKomisi();


        $anggotaDprd = $this->anggotaDprdRepository->findWithoutFail($id);

        if (empty($anggotaDprd)) {
            Flash::error('Anggota Dprd not found');
            return redirect(route('anggotaDprds.index'));
        }

        return view('anggota_dprds.edit')
            ->with('anggotaDprd', $anggotaDprd)
            ->with('masterwilayah', $masterwilayah)
            ->with('masterkomisi', $masterkomisi);
    }

    /**
     * Update the specified AnggotaDprd in storage.
     *
     * @param  int              $id
     * @param UpdateAnggotaDprdRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAnggotaDprdRequest $request)
    {
        $anggotaDprd = $this->anggotaDprdRepository->findWithoutFail($id);

        if (empty($anggotaDprd)) {
            Flash::error('Anggota Dprd not found');
            return redirect(route('anggotaDprds.index'));
        }

        $input = $request->all();
        $user = Auth::user();
        $input['updated_by'] = $user->id;
        $input['history_updated'] = savingHistory($user, $anggotaDprd->history_updated);

        if(@$request->foto) {
            $input['foto'] = $this->saveFile->setImage(@$request->foto)->setStorage('dewan')->handle();
        }

        $anggotaDprd = $this->anggotaDprdRepository->update($input, $id);

        Flash::success('Anggota Dprd updated successfully.');
        return redirect(route('anggotaDprds.index'));
    }

    /**
     * Remove the specified AnggotaDprd from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $anggotaDprd = $this->anggotaDprdRepository->findWithoutFail($id);
        $user = Auth::user();

        if (empty($anggotaDprd)) {
            Flash::error('Anggota Dprd not found');
            return redirect(route('anggotaDprds.index'));
        }

         $updated = [
            'history_updated' => savingHistory($user, $anggotaDprd->history_updated, true)
        ];

        $this->anggotaDprdRepository->update($updated, $id);

        $this->anggotaDprdRepository->delete($id);

        Flash::success('Anggota Dprd deleted successfully.');
        return redirect(route('anggotaDprds.index'));
    }

    /**
     * Store data AnggotaDprd from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $anggotaDprd = $this->anggotaDprdRepository->create($item->toArray());
            });
        });

        Flash::success('Anggota Dprd saved successfully.');
        return redirect(route('anggotaDprds.index'));
    }
}
