<?php

namespace App\Http\Controllers;

use App\DataTables\VersionDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateVersionRequest;
use App\Http\Requests\UpdateVersionRequest;
use App\Repositories\VersionRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class VersionController extends AppBaseController
{
    /** @var  VersionRepository */
    private $versionRepository;

    public function __construct(VersionRepository $versionRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:version-edit', ['only' => ['edit']]);
        $this->middleware('can:version-store', ['only' => ['store']]);
        $this->middleware('can:version-show', ['only' => ['show']]);
        $this->middleware('can:version-update', ['only' => ['update']]);
        $this->middleware('can:version-delete', ['only' => ['delete']]);
        $this->middleware('can:version-create', ['only' => ['create']]);
        $this->versionRepository = $versionRepo;
    }

    /**
     * Display a listing of the Version.
     *
     * @param VersionDataTable $versionDataTable
     * @return Response
     */
    public function index(VersionDataTable $versionDataTable)
    {
        return $versionDataTable->render('versions.index');
    }

    /**
     * Show the form for creating a new Version.
     *
     * @return Response
     */
    public function create()
    {


        return view('versions.create');
    }

    /**
     * Store a newly created Version in storage.
     *
     * @param CreateVersionRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $version = $this->versionRepository->create($input);

        Flash::success('Version saved successfully.');
        return redirect(route('versions.index'));
    }

    /**
     * Display the specified Version.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $version = $this->versionRepository->findWithoutFail($id);

        if (empty($version)) {
            Flash::error('Version not found');
            return redirect(route('versions.index'));
        }

        return view('versions.show')->with('version', $version);
    }

    /**
     * Show the form for editing the specified Version.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {



        $version = $this->versionRepository->findWithoutFail($id);

        if (empty($version)) {
            Flash::error('Version not found');
            return redirect(route('versions.index'));
        }

        return view('versions.edit')
            ->with('version', $version);
    }

    /**
     * Update the specified Version in storage.
     *
     * @param  int              $id
     * @param UpdateVersionRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $version = $this->versionRepository->findWithoutFail($id);

        if (empty($version)) {
            Flash::error('Version not found');
            return redirect(route('versions.index'));
        }

        $input = $request->all();
        $version = $this->versionRepository->update($input, $id);

        Flash::success('Version updated successfully.');
        return redirect(route('versions.index'));
    }

    /**
     * Remove the specified Version from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $version = $this->versionRepository->findWithoutFail($id);

        if (empty($version)) {
            Flash::error('Version not found');
            return redirect(route('versions.index'));
        }

        $this->versionRepository->delete($id);

        Flash::success('Version deleted successfully.');
        return redirect(route('versions.index'));
    }

    /**
     * Store data Version from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function ($reader) {
            $reader->each(function ($item) {
                $version = $this->versionRepository->create($item->toArray());
            });
        });

        Flash::success('Version saved successfully.');
        return redirect(route('versions.index'));
    }


}
