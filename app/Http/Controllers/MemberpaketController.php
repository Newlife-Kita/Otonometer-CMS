<?php

namespace App\Http\Controllers;

use App\DataTables\MemberpaketDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateMemberpaketRequest;
use App\Http\Requests\UpdateMemberpaketRequest;
use App\Repositories\MemberpaketRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel; 

class MemberpaketController extends AppBaseController
{
    /** @var  MemberpaketRepository */
    private $memberpaketRepository;

    public function __construct(MemberpaketRepository $memberpaketRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:memberpaket-edit', ['only' => ['edit']]);
        $this->middleware('can:memberpaket-store', ['only' => ['store']]);
        $this->middleware('can:memberpaket-show', ['only' => ['show']]);
        $this->middleware('can:memberpaket-update', ['only' => ['update']]);
        $this->middleware('can:memberpaket-delete', ['only' => ['delete']]);
        $this->middleware('can:memberpaket-create', ['only' => ['create']]);
        $this->memberpaketRepository = $memberpaketRepo;
    }

    /**
     * Display a listing of the Memberpaket.
     *
     * @param MemberpaketDataTable $memberpaketDataTable
     * @return Response
     */
    public function index(MemberpaketDataTable $memberpaketDataTable)
    {
        return $memberpaketDataTable->render('memberpakets.index');
    }

    /**
     * Show the form for creating a new Memberpaket.
     *
     * @return Response
     */
    public function create()
    {
        return redirect(route('memberpakets.index'));
        return view('memberpakets.create');
    }

    /**
     * Store a newly created Memberpaket in storage.
     *
     * @param CreateMemberpaketRequest $request
     *
     * @return Response
     */
    public function store(CreateMemberpaketRequest $request)
    {
        return redirect(route('memberpakets.index'));
        $input = $request->all();

        $memberpaket = $this->memberpaketRepository->create($input);

        Flash::success('Memberpaket saved successfully.');
        return redirect(route('memberpakets.index'));
    }

    /**
     * Display the specified Memberpaket.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $memberpaket = $this->memberpaketRepository->findWithoutFail($id);

        if (empty($memberpaket)) {
            Flash::error('Memberpaket not found');
            return redirect(route('memberpakets.index'));
        }

        return view('memberpakets.show')->with('memberpaket', $memberpaket);
    }

    /**
     * Show the form for editing the specified Memberpaket.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        return redirect(route('memberpakets.index'));
        $memberpaket = $this->memberpaketRepository->findWithoutFail($id);

        if (empty($memberpaket)) {
            Flash::error('Memberpaket not found');
            return redirect(route('memberpakets.index'));
        }

        return view('memberpakets.edit')
            ->with('memberpaket', $memberpaket);
    }

    /**
     * Update the specified Memberpaket in storage.
     *
     * @param  int              $id
     * @param UpdateMemberpaketRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMemberpaketRequest $request)
    {
        return redirect(route('memberpakets.index'));
        $memberpaket = $this->memberpaketRepository->findWithoutFail($id);

        if (empty($memberpaket)) {
            Flash::error('Memberpaket not found');
            return redirect(route('memberpakets.index'));
        }

        $input = $request->all();
        $memberpaket = $this->memberpaketRepository->update($input, $id);

        Flash::success('Memberpaket updated successfully.');
        return redirect(route('memberpakets.index'));
    }

    /**
     * Remove the specified Memberpaket from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $memberpaket = $this->memberpaketRepository->findWithoutFail($id);

        if (empty($memberpaket)) {
            Flash::error('Memberpaket not found');
            return redirect(route('memberpakets.index'));
        }

        $this->memberpaketRepository->delete($id);

        Flash::success('Memberpaket deleted successfully.');
        return redirect(route('memberpakets.index'));
    }

    /**
     * Store data Memberpaket from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $memberpaket = $this->memberpaketRepository->create($item->toArray());
            });
        });

        Flash::success('Memberpaket saved successfully.');
        return redirect(route('memberpakets.index'));
    }
}
