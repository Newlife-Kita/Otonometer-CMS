<?php

namespace App\Http\Controllers;

use App\DataTables\LogSubmitDataTable;
use App\DataTables\LogUploadDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateLogUploadRequest;
use App\Http\Requests\UpdateLogUploadRequest;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\LogCmsRepository;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class LogUploadController extends AppBaseController
{
    /** @var  LogCmsRepository */
    private $logUploadRepository;

    public function __construct(LogCmsRepository $logUploadRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:logUpload-edit', ['only' => ['edit']]);
        $this->middleware('can:logUpload-store', ['only' => ['store']]);
        $this->middleware('can:logUpload-show', ['only' => ['show']]);
        $this->middleware('can:logUpload-update', ['only' => ['update']]);
        $this->middleware('can:logUpload-delete', ['only' => ['delete']]);
        $this->middleware('can:logUpload-create', ['only' => ['create']]);
        $this->logUploadRepository = $logUploadRepo;
    }

    /**
     * Display a listing of the LogUpload.
     *
     * @param LogUploadDataTable $logUploadDataTable
     * @return Response
     */
    public function index(LogUploadDataTable $logUploadDataTable)
    {
        return $logUploadDataTable->render('log_uploads.index');
    }


    public function index_submit(LogSubmitDataTable $logSubmitDataTable)
    {
        return $logSubmitDataTable->render('log_uploads.index_submit');
    }

    /**
     * Show the form for creating a new LogUpload.
     *
     * @return Response
     */
    public function create()
    {


        return view('log_uploads.create');
    }

    /**
     * Store a newly created LogUpload in storage.
     *
     * @param CreateLogUploadRequest $request
     *
     * @return Response
     */
    public function store(CreateLogUploadRequest $request)
    {
        $input = $request->all();

        $logUpload = $this->logUploadRepository->create($input);

        Flash::success('Log Upload saved successfully.');
        return redirect(route('logUploads.index'));
    }

    /**
     * Display the specified LogUpload.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $logUpload = $this->logUploadRepository->findWithoutFail($id);

        if (empty($logUpload)) {
            Flash::error('Log Upload not found');
            return redirect(route('logUploads.index'));
        }

        return view('log_uploads.show')->with('logUpload', $logUpload);
    }

    /**
     * Show the form for editing the specified LogUpload.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {



        $logUpload = $this->logUploadRepository->findWithoutFail($id);

        if (empty($logUpload)) {
            Flash::error('Log Upload not found');
            return redirect(route('logUploads.index'));
        }

        return view('log_uploads.edit')
            ->with('logUpload', $logUpload);
    }

    /**
     * Update the specified LogUpload in storage.
     *
     * @param  int              $id
     * @param UpdateLogUploadRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLogUploadRequest $request)
    {
        $logUpload = $this->logUploadRepository->findWithoutFail($id);

        if (empty($logUpload)) {
            Flash::error('Log Upload not found');
            return redirect(route('logUploads.index'));
        }

        $input = $request->all();
        $logUpload = $this->logUploadRepository->update($input, $id);

        Flash::success('Log Upload updated successfully.');
        return redirect(route('logUploads.index'));
    }

    /**
     * Remove the specified LogUpload from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $logUpload = $this->logUploadRepository->findWithoutFail($id);

        if (empty($logUpload)) {
            Flash::error('Log Upload not found');
            return redirect(route('logUploads.index'));
        }

        $this->logUploadRepository->delete($id);

        Flash::success('Log Upload deleted successfully.');
        return redirect(route('logUploads.index'));
    }

    /**
     * Store data LogUpload from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function ($reader) {
            $reader->each(function ($item) {
                $logUpload = $this->logUploadRepository->create($item->toArray());
            });
        });

        Flash::success('Log Upload saved successfully.');
        return redirect(route('logUploads.index'));
    }
}
