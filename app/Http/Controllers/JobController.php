<?php

namespace App\Http\Controllers;

use App\DataTables\JobDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Repositories\JobRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BahasaRepository;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class JobController extends AppBaseController
{
    /** @var  JobRepository */
    private $jobRepository;
    private $bahasaRepopsitory;

    public function __construct(JobRepository $jobRepo, BahasaRepository $bahasaRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:job-edit', ['only' => ['edit']]);
        $this->middleware('can:job-store', ['only' => ['store']]);
        $this->middleware('can:job-show', ['only' => ['show']]);
        $this->middleware('can:job-update', ['only' => ['update']]);
        $this->middleware('can:job-delete', ['only' => ['delete']]);
        $this->middleware('can:job-create', ['only' => ['create']]);
        $this->jobRepository = $jobRepo;
        $this->bahasaRepopsitory = $bahasaRepo;
    }

    /**
     * Display a listing of the Job.
     *
     * @param JobDataTable $jobDataTable
     * @return Response
     */
    public function index(JobDataTable $jobDataTable)
    {
        $akses = Auth::user();
        if(!$akses->can('job-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return $jobDataTable->render('jobs.index');
    }

    /**
     * Show the form for creating a new Job.
     *
     * @return Response
     */
    public function create()
    {
        $akses = Auth::user();
        if(!$akses->can('job-create')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $bahasa = $this->bahasaRepopsitory->all();
        return view('jobs.create')->with('bahasa', $bahasa);
    }

    /**
     * Store a newly created Job in storage.
     *
     * @param CreateJobRequest $request
     *
     * @return Response
     */
    public function store(CreateJobRequest $request)
    {
        $input = $request->all();

        $job = $this->jobRepository->create($input);

        Flash::success('Job saved successfully.');
        return redirect(route('jobs.index'));
    }

    /**
     * Display the specified Job.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $akses = Auth::user();
        if(!$akses->can('job-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $job = $this->jobRepository->findWithoutFail($id);

        if (empty($job)) {
            Flash::error('Job not found');
            return redirect(route('jobs.index'));
        }

        return view('jobs.show')->with('job', $job);
    }

    /**
     * Show the form for editing the specified Job.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $akses = Auth::user();
        if(!$akses->can('job-edit')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        $job = $this->jobRepository->findWithoutFail($id);

        if (empty($job)) {
            Flash::error('Job not found');
            return redirect(route('jobs.index'));
        }

        $bahasa = $this->bahasaRepopsitory->all();

        return view('jobs.edit')
            ->with('job', $job)
            ->with('bahasa', $bahasa);
    }

    /**
     * Update the specified Job in storage.
     *
     * @param  int              $id
     * @param UpdateJobRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateJobRequest $request)
    {
        $job = $this->jobRepository->findWithoutFail($id);

        if (empty($job)) {
            Flash::error('Job not found');
            return redirect(route('jobs.index'));
        }

        $input = $request->all();
        $job = $this->jobRepository->update($input, $id);

        Flash::success('Job updated successfully.');
        return redirect(route('jobs.index'));
    }

    /**
     * Remove the specified Job from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $job = $this->jobRepository->findWithoutFail($id);

        if (empty($job)) {
            Flash::error('Job not found');
            return redirect(route('jobs.index'));
        }

        $this->jobRepository->delete($id);

        Flash::success('Job deleted successfully.');
        return redirect(route('jobs.index'));
    }

    /**
     * Store data Job from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $job = $this->jobRepository->create($item->toArray());
            });
        });

        Flash::success('Job saved successfully.');
        return redirect(route('jobs.index'));
    }
}
