<?php

namespace App\Http\Controllers;

use App\DataTables\MemberaktifitasDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateMemberaktifitasRequest;
use App\Http\Requests\UpdateMemberaktifitasRequest;
use App\Repositories\MemberaktifitasRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel; 

class MemberaktifitasController extends AppBaseController
{
    /** @var  MemberaktifitasRepository */
    private $memberaktifitasRepository;

    public function __construct(MemberaktifitasRepository $memberaktifitasRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:memberaktifitas-edit', ['only' => ['edit']]);
        $this->middleware('can:memberaktifitas-store', ['only' => ['store']]);
        $this->middleware('can:memberaktifitas-show', ['only' => ['show']]);
        $this->middleware('can:memberaktifitas-update', ['only' => ['update']]);
        $this->middleware('can:memberaktifitas-delete', ['only' => ['delete']]);
        $this->middleware('can:memberaktifitas-create', ['only' => ['create']]);
        $this->memberaktifitasRepository = $memberaktifitasRepo;
    }

    /**
     * Display a listing of the Memberaktifitas.
     *
     * @param MemberaktifitasDataTable $memberaktifitasDataTable
     * @return Response
     */
    public function index(MemberaktifitasDataTable $memberaktifitasDataTable)
    {
        return $memberaktifitasDataTable->render('memberaktifitas.index');
    }

    /**
     * Show the form for creating a new Memberaktifitas.
     *
     * @return Response
     */
    public function create()
    {
        return redirect(route('memberaktifitas.index'));
        return view('memberaktifitas.create');
    }

    /**
     * Store a newly created Memberaktifitas in storage.
     *
     * @param CreateMemberaktifitasRequest $request
     *
     * @return Response
     */
    public function store(CreateMemberaktifitasRequest $request)
    {
        return redirect(route('memberaktifitas.index'));
        $input = $request->all();

        $memberaktifitas = $this->memberaktifitasRepository->create($input);

        Flash::success('Memberaktifitas saved successfully.');
        return redirect(route('memberaktifitas.index'));
    }

    /**
     * Display the specified Memberaktifitas.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $memberaktifitas = $this->memberaktifitasRepository->findWithoutFail($id);

        if (empty($memberaktifitas)) {
            Flash::error('Memberaktifitas not found');
            return redirect(route('memberaktifitas.index'));
        }

        return view('memberaktifitas.show')->with('memberaktifitas', $memberaktifitas);
    }

    /**
     * Show the form for editing the specified Memberaktifitas.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        return redirect(route('memberaktifitas.index'));

        $memberaktifitas = $this->memberaktifitasRepository->findWithoutFail($id);

        if (empty($memberaktifitas)) {
            Flash::error('Memberaktifitas not found');
            return redirect(route('memberaktifitas.index'));
        }

        return view('memberaktifitas.edit')
            ->with('memberaktifitas', $memberaktifitas);
    }

    /**
     * Update the specified Memberaktifitas in storage.
     *
     * @param  int              $id
     * @param UpdateMemberaktifitasRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMemberaktifitasRequest $request)
    {
        return redirect(route('memberaktifitas.index'));
        $memberaktifitas = $this->memberaktifitasRepository->findWithoutFail($id);

        if (empty($memberaktifitas)) {
            Flash::error('Memberaktifitas not found');
            return redirect(route('memberaktifitas.index'));
        }

        $input = $request->all();
        $memberaktifitas = $this->memberaktifitasRepository->update($input, $id);

        Flash::success('Memberaktifitas updated successfully.');
        return redirect(route('memberaktifitas.index'));
    }

    /**
     * Remove the specified Memberaktifitas from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $memberaktifitas = $this->memberaktifitasRepository->findWithoutFail($id);

        if (empty($memberaktifitas)) {
            Flash::error('Memberaktifitas not found');
            return redirect(route('memberaktifitas.index'));
        }

        $this->memberaktifitasRepository->delete($id);

        Flash::success('Memberaktifitas deleted successfully.');
        return redirect(route('memberaktifitas.index'));
    }

    /**
     * Store data Memberaktifitas from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $memberaktifitas = $this->memberaktifitasRepository->create($item->toArray());
            });
        });

        Flash::success('Memberaktifitas saved successfully.');
        return redirect(route('memberaktifitas.index'));
    }
}
