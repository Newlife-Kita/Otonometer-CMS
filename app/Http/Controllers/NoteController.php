<?php

namespace App\Http\Controllers;

use App\DataTables\NoteDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Repositories\NoteRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BahasaRepository;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class NoteController extends AppBaseController
{
    /** @var  NoteRepository */
    private $noteRepository;
    private $bahasaRepository;

    public function __construct(NoteRepository $noteRepo, BahasaRepository $bahasaRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:note-edit', ['only' => ['edit']]);
        $this->middleware('can:note-store', ['only' => ['store']]);
        $this->middleware('can:note-show', ['only' => ['show']]);
        $this->middleware('can:note-update', ['only' => ['update']]);
        $this->middleware('can:note-delete', ['only' => ['delete']]);
        $this->middleware('can:note-create', ['only' => ['create']]);
        $this->noteRepository = $noteRepo;
        $this->bahasaRepository = $bahasaRepo;
    }

    /**
     * Display a listing of the Note.
     *
     * @param NoteDataTable $noteDataTable
     * @return Response
     */
    public function index(NoteDataTable $noteDataTable)
    {
        $akses = Auth::user();
        if(!$akses->can('note-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return $noteDataTable->render('notes.index');
    }

    /**
     * Show the form for creating a new Note.
     *
     * @return Response
     */
    public function create()
    {
        $akses = Auth::user();
        if(!$akses->can('note-create')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $bahasa = $this->bahasaRepository->all();
        return view('notes.create')->with('bahasa',$bahasa);
    }

    /**
     * Store a newly created Note in storage.
     *
     * @param CreateNoteRequest $request
     *
     * @return Response
     */
    public function store(CreateNoteRequest $request)
    {
        $input = $request->all();
        $note = $this->noteRepository->create($input);

        Flash::success('Catatan Sektor/Bidang  successfully.');
        return redirect(route('notes.index'));
    }

    /**
     * Display the specified Note.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $akses = Auth::user();
        if(!$akses->can('note-show')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $note = $this->noteRepository->findWithoutFail($id);

        if (empty($note)) {
            Flash::error('Catatan Sektor/Bidang not found');
            return redirect(route('notes.index'));
        }

        return view('notes.show')->with('note', $note);
    }

    /**
     * Show the form for editing the specified Note.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $akses = Auth::user();
        if(!$akses->can('note-edit')){
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $note = $this->noteRepository->findWithoutFail($id);

        if (empty($note)) {
            Flash::error('Catatan Sektor/Bidang not found');
            return redirect(route('notes.index'));
        }

        $bahasa = $this->bahasaRepository->all();
        return view('notes.edit')
            ->with('note', $note)
            ->with('bahasa',$bahasa);
    }

    /**
     * Update the specified Note in storage.
     *
     * @param  int              $id
     * @param UpdateNoteRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateNoteRequest $request)
    {
        $note = $this->noteRepository->findWithoutFail($id);

        if (empty($note)) {
            Flash::error('Catatan Sektor/Bidang not found');
            return redirect(route('notes.index'));
        }

        $input = $request->all();
        $note = $this->noteRepository->update($input, $id);

        Flash::success('Catatan Sektor/Bidang updated successfully.');
        return redirect(route('notes.index'));
    }

    /**
     * Remove the specified Note from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $note = $this->noteRepository->findWithoutFail($id);

        if (empty($note)) {
            Flash::error('Catatan Sektor/Bidang not found');
            return redirect(route('notes.index'));
        }

        $this->noteRepository->delete($id);

        Flash::success('Catatan Sektor/Bidang deleted successfully.');
        return redirect(route('notes.index'));
    }

    /**
     * Store data Note from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $note = $this->noteRepository->create($item->toArray());
            });
        });

        Flash::success('Catatan Sektor/Bidang  successfully.');
        return redirect(route('notes.index'));
    }
}
