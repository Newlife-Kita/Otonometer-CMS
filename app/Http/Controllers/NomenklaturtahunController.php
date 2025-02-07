<?php

namespace App\Http\Controllers;

use App\DataTables\NomenklaturtahunDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateNomenklaturtahunRequest;
use App\Http\Requests\UpdateNomenklaturtahunRequest;
use App\Repositories\NomenklaturtahunRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\NomenklaturRepository;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class NomenklaturtahunController extends AppBaseController
{
    /** @var  NomenklaturtahunRepository */
    private $nomenklaturtahunRepository;
    private $nomenklaturRepository;

    public function __construct(NomenklaturtahunRepository $nomenklaturtahunRepo, NomenklaturRepository $nomenklaturRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:nomenklaturtahun-edit', ['only' => ['edit']]);
        $this->middleware('can:nomenklaturtahun-store', ['only' => ['store']]);
        $this->middleware('can:nomenklaturtahun-show', ['only' => ['show']]);
        $this->middleware('can:nomenklaturtahun-update', ['only' => ['update']]);
        $this->middleware('can:nomenklaturtahun-delete', ['only' => ['delete']]);
        $this->middleware('can:nomenklaturtahun-create', ['only' => ['create']]);
        $this->nomenklaturtahunRepository = $nomenklaturtahunRepo;
        $this->nomenklaturRepository = $nomenklaturRepo;
    }

    /**
     * Display a listing of the Nomenklaturtahun.
     *
     * @param NomenklaturtahunDataTable $nomenklaturtahunDataTable
     * @return Response
     */
    public function index(NomenklaturtahunDataTable $nomenklaturtahunDataTable)
    {
        $akses = Auth::user();
        if (!$akses->can('nomenklaturtahun-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return $nomenklaturtahunDataTable->render('nomenklaturtahuns.index');
    }

    /**
     * Show the form for creating a new Nomenklaturtahun.
     *
     * @return Response
     */
    public function create()
    {
        $akses = Auth::user();
        if (!$akses->can('nomenklaturtahun-create')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $nomenklatur = $this->nomenklaturRepository->get_array_year();
        return view('nomenklaturtahuns.create')
            ->with('nomenklaturtahun', null)
            ->with('nomenklatur', $nomenklatur);
    }

    /**
     * Store a newly created Nomenklaturtahun in storage.
     *
     * @param CreateNomenklaturtahunRequest $request
     *
     * @return Response
     */
    public function store(CreateNomenklaturtahunRequest $request)
    {
        $input = $request->all();

        // cek apa tahun data sudah pernah dibuat?
        $nomenklatur = $this->nomenklaturtahunRepository->where('tahun', @$input['tahun'])->first();
        if (!empty($nomenklatur)) {
            return redirect()->back()->withInput($input)->withErrors(['Tahun Data ' . @$input["tahun"] . ' sudah ada']);
        }

        // cek apa table sudah pernah ada?
        $tablename = 'nomenklatur_amount_' . @$input['tahun'];
        if (Schema::hasTable($tablename)) {
        } else {
            Schema::create($tablename, function ($table) use ($input) {
                $table->increments('id');
                $table->integer('id_bidang')->nullable();
                $table->integer('id_nomenklatur')->nullable();
                $table->integer('id_wilayah')->nullable();
                $table->year('tahun')->default(@$input['tahun']);
                $table->decimal('nilai', 16, 3)->nullable();
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
                $table->longText('history_updated')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['id_bidang']);
                $table->index(['id_wilayah']);
                $table->index(['tahun']);
                $table->index(['nilai']);
                $table->index(['id_bidang', 'id_wilayah']);
                $table->index(['id_bidang', 'id_wilayah', 'nilai']);
                $table->index(['id_bidang', 'id_wilayah', 'tahun', 'nilai']);
            });
        }
        $this->nomenklaturtahunRepository->create(['tahun' => @$input['tahun']]);

        Flash::success('Tahun data saved successfully.');
        return redirect(route('nomenklaturtahuns.index'));
    }

    /**
     * Display the specified Nomenklaturtahun.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $akses = Auth::user();
        if (!$akses->can('nomenklaturtahun-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $nomenklaturtahun = $this->nomenklaturtahunRepository->where('tahun', $id)->get();

        if ($nomenklaturtahun->couont() < 1) {
            Flash::error('Tahun data not found');
            return redirect(route('nomenklaturtahuns.index'));
        }

        return view('nomenklaturtahuns.show')->with('tahun', $id)->with('nomenklaturtahun', $nomenklaturtahun);
    }


    /**
     * Remove the specified Nomenklaturtahun from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $nomenklaturtahun = $this->nomenklaturtahunRepository->findWithoutFail($id);

        if (empty($nomenklaturtahun)) {
            Flash::error('Tahun data not found');
            return redirect(route('nomenklaturtahuns.index'));
        }

        // cek apa table sudah pernah ada?
        $tablename = 'nomenklatur_amount_' . @$nomenklaturtahun->tahun;
        if (Schema::hasTable($tablename)) {
            Schema::dropIfExists($tablename);
        }

        $this->nomenklaturtahunRepository->whereId($id)->forceDelete();

        Flash::success('Tahun data deleted successfully.');
        return redirect(route('nomenklaturtahuns.index'));
    }

    /**
     * Store data Nomenklaturtahun from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function ($reader) {
            $reader->each(function ($item) {
                $nomenklaturtahun = $this->nomenklaturtahunRepository->create($item->toArray());
            });
        });

        Flash::success('Tahun data saved successfully.');
        return redirect(route('nomenklaturtahuns.index'));
    }

    public function edit($id, Request $request)
    {
        $akses = Auth::user();

        if (!$akses->can("nomenklaturtahun-edit")) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        $nomenklaturTahun = $this->nomenklaturtahunRepository->findWithoutFail($id, ["tahun", "active", "default_year"]);

        return view('nomenklaturtahuns.edit')
            ->with('id', $id)
            ->with('tahun', $nomenklaturTahun->tahun)
            ->with('active', $nomenklaturTahun->active)
            ->with('default_year', $nomenklaturTahun->default_year);
    }

    public function update($id, Request $request)
    {
        $akses = Auth::user();

        if (!$akses->can("nomenklaturtahun-update")) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        $nomenklaturTahun = $this->nomenklaturtahunRepository->update($request->except('default_year'), $id);

        if ($request->default_year == 1) {
            $this->nomenklaturtahunRepository->where('default_year', 1)->update(['default_year' => 0]);
            $this->nomenklaturtahunRepository->where('id', $id)->update(['default_year' => 1]);
        }

        Flash::success('Tahun data updated successfully.');
        return redirect(route('nomenklaturtahuns.index'));
    }


}
