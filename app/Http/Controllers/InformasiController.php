<?php

namespace App\Http\Controllers;

use App\DataTables\InformasiDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateInformasiRequest;
use App\Http\Requests\UpdateInformasiRequest;
use App\Repositories\InformasiRepository;
use App\Services\UploadFileService;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Services\SaveFileService;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\DownloadFileService;

class InformasiController extends AppBaseController
{
    /** @var  InformasiRepository */
    private $informasiRepository;
    private $saveFile;

    private $uploadFile;

    private $downloadFile;



    private $path = 'informasi';

    public function __construct(InformasiRepository $informasiRepo, SaveFileService $saveFile, UploadFileService $uploadFile, DownloadFileService $downloadFile)
    {
        $this->middleware('auth');
        $this->middleware('can:informasi-edit', ['only' => ['edit']]);
        $this->middleware('can:informasi-store', ['only' => ['store']]);
        $this->middleware('can:informasi-show', ['only' => ['show']]);
        $this->middleware('can:informasi-update', ['only' => ['update']]);
        $this->middleware('can:informasi-delete', ['only' => ['delete']]);
        $this->middleware('can:informasi-create', ['only' => ['create']]);
        $this->informasiRepository = $informasiRepo;
        $this->saveFile = $saveFile;
        $this->uploadFile = $uploadFile;
        $this->downloadFile = $downloadFile;
    }

    /**
     * Display a listing of the Informasi.
     *
     * @param InformasiDataTable $informasiDataTable
     * @return Response
     */
    public function index(InformasiDataTable $informasiDataTable)
    {
        $akses = Auth::user();
        if(!$akses->can('informasi-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return $informasiDataTable->render('informasis.index');
    }

    /**
     * Show the form for creating a new Informasi.
     *
     * @return Response
     */
    public function create()
    {
        $akses = Auth::user();
        if(!$akses->can('informasi-create')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return view('informasis.create');
    }

    /**
     * Store a newly created Informasi in storage.
     *
     * @param CreateInformasiRequest $request
     *
     * @return Response
     */
    public function store(CreateInformasiRequest $request)
    {
        $user = Auth::user();
        $input = $request->all();
        $input['created_by'] = $user->id;

        if (@$request->file) {
            $input['file'] = $this->uploadFile->uploadFile($this->path, 'file', @$request->file);
            // $input['foto'] = $this->uploadFile->uploadFile($this->path,'dprd',@$request->foto);
        } else {
            $input['file'] = null;
        }


        $informasi = $this->informasiRepository->create($input);

        Flash::success('File Informasi saved successfully.');
        return redirect(route('informasis.index'));
    }

    /**
     * Display the specified Informasi.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $akses = Auth::user();
        if(!$akses->can('informasi-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $informasi = $this->informasiRepository->findWithoutFail($id);

        if (empty($informasi)) {
            Flash::error('File Informasi not found');
            return redirect(route('informasis.index'));
        }

        return view('informasis.show')->with('informasi', $informasi);
    }

    /**
     * Show the form for editing the specified Informasi.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $akses = Auth::user();
        if(!$akses->can('informasi-edit')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }


        $informasi = $this->informasiRepository->findWithoutFail($id);

        if (empty($informasi)) {
            Flash::error('File Informasi not found');
            return redirect(route('informasis.index'));
        }

        return view('informasis.edit')
            ->with('informasi', $informasi);
    }

    /**
     * Update the specified Informasi in storage.
     *
     * @param  int              $id
     * @param UpdateInformasiRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInformasiRequest $request)
    {
        $informasi = $this->informasiRepository->findWithoutFail($id);

        if (empty($informasi)) {
            Flash::error('File Informasi not found');
            return redirect(route('informasis.index'));
        }

        $input = $request->all();
        $user = Auth::user();
        $input['updated_by'] = $user->id;

        if (@$request->file) {
            // $input['file'] = $this->saveFile->setModel(@$informasi->file)->setImage(@$request->file)->setStorage('fileInfo')->handle();
            $input['file'] = $this->uploadFile->uploadFile($this->path, 'file', @$request->file, $informasi, 'update');
        } else {
            $input['file'] = @$informasi->file;
        }

        $informasi = $this->informasiRepository->update($input, $id);

        Flash::success('File Informasi updated successfully.');
        return redirect(route('informasis.index'));
    }

    /**
     * Remove the specified Informasi from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $informasi = $this->informasiRepository->findWithoutFail($id);

        if (empty($informasi)) {
            Flash::error('File Informasi not found');
            return redirect(route('informasis.index'));
        }

        $this->informasiRepository->delete($id);

        Flash::success('File Informasi deleted successfully.');
        return redirect(route('informasis.index'));
    }

    public function download($id)
    {
        $informasi = $this->informasiRepository->findWithoutFail($id);

        if (!$informasi) {
            Flash::error('File not found');
            return back();
        }

        $filename = $informasi->file;

        return $this->downloadFile->downloadFile($filename);
    }

    public function downloadApi($filename)
    {
        return $this->downloadFile->downloadFile($filename);
    }

    /**
     * Store data Informasi from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function ($reader) {
            $reader->each(function ($item) {
                $informasi = $this->informasiRepository->create($item->toArray());
            });
        });

        Flash::success('File Informasi saved successfully.');
        return redirect(route('informasis.index'));
    }
}
