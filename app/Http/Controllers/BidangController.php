<?php

namespace App\Http\Controllers;

use App\DataTables\BidangDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateBidangRequest;
use App\Http\Requests\UpdateBidangRequest;
use App\Repositories\BidangRepository;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BahasaRepository;
use App\Repositories\NomenklaturRepository;
use App\Repositories\NomenklaturtahunRepository;
use App\Repositories\NoteRepository;
use App\Repositories\SatuanRepository;
use App\Repositories\SumberdataRepository;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class BidangController extends AppBaseController
{
    /** @var  BidangRepository */
    private $bidangRepository;
    private $bahasaRepository;
    private $satuanRepository;
    private $noteRepository;
    private $sumberRepository;
    private $tahunRepository;

    public function __construct(BidangRepository $bidangRepo, BahasaRepository $bahasaRepo, SatuanRepository $satuanRepo, NoteRepository $noteRepo, SumberdataRepository $sumberRepo, NomenklaturRepository $tahunRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:bidang-edit', ['only' => ['edit']]);
        $this->middleware('can:bidang-store', ['only' => ['store']]);
        $this->middleware('can:bidang-show', ['only' => ['show']]);
        $this->middleware('can:bidang-update', ['only' => ['update']]);
        $this->middleware('can:bidang-delete', ['only' => ['delete']]);
        $this->middleware('can:bidang-create', ['only' => ['create']]);
        $this->bidangRepository = $bidangRepo;
        $this->bahasaRepository = $bahasaRepo;
        $this->satuanRepository = $satuanRepo;
        $this->noteRepository = $noteRepo;
        $this->sumberRepository = $sumberRepo;
        $this->tahunRepository = $tahunRepo;
    }

    /**
     * Display a listing of the Bidang.
     *
     * @param BidangDataTable $bidangDataTable
     * @return Response
     */
    public function index($bidangDataTable) 
    {
        /** @var User $akses */
        $akses = Auth::user();
        if (!$akses->can('bidang-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        return $bidangDataTable->render('bidangs.index');
    }

    public function keuangan_index()
    {
        $akses = Auth::user();
        if (!$akses->can('bidang-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $code = '1';
        // $parent = $this->bidangRepository->where('kode', $code)->first();

        // return $dataTable->setParents($parent->id)->renders('bidangs.keuangan.index', [], [], ['parent' => $parent, 'subparent' => null]);

        $bidang = $this->bidangRepository->get_sektor_table($code);
        return view('bidangs.keuangan.show')->with('bidang', $bidang)->with('code', $code);
    }

    // public function keuangan_childs($id, BidangKeuanganChildsDataTable $dataTable)
    // {

    //     $parent = $this->bidangRepository->findWithoutFail($id);
    //     if (empty($parent)) {
    //         Flash::error('Sektor/Bidang not found.');
    //         return redirect(route('sektor-bidang.keuangan.index'));
    //     }

    //     $subparent = $this->bidangRepository->findWithoutFail($parent->id_parent);

    //     return $dataTable->setParents($id)->renders('bidangs.keuangan.index', [], [], ['parent' => $parent, 'subparent' => @$subparent]);
    // }

    public function keuangan_create($id, Request $request)
    {
        $akses = Auth::user();
        if (!$akses->can('bidang-create')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $parent = $this->bidangRepository->findWithoutFail($id);
        if (empty($parent)) {
            Flash::error('Sektor/Bidang not found.');
            return redirect(route('sektor-bidang.keuangan.index'));
        }
        $tahun = $this->tahunRepository->select('kode as tahun')->whereNull('id_parent')->orderBy('kode', 'asc')->pluck('tahun');
        $satuan = $this->satuanRepository->getArrayData();
        $bahasa = $this->bahasaRepository->all();
        $note = $this->noteRepository->getArrayData();
        $sumber = $this->sumberRepository->getArrayData();
        $segment = 'keuangan'; //ucfirst((strtolower($request->segment(2))));
        if (strtolower($parent->nama) == $segment) {
            $parent->kode = '01.00.00.00.00.00';
        }

        return view('bidangs.create')->with('parent', $parent)
            ->with('segment', $segment)
            ->with('bahasa', $bahasa)
            ->with('satuan', $satuan)
            ->with('note', $note)
            ->with('sumber', $sumber)
            ->with('tahun', $tahun)
            ->with('id', $id);
    }

    public function ekonomi_index()
    {
        $akses = Auth::user();
        if (!$akses->can('bidang-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $code = '2';
        // $parent = $this->bidangRepository->where('kode', $code)->first();

        // return $dataTable->setParents($parent->id)->renders('bidangs.ekonomi.index', [], [], ['parent' => $parent, 'subparent' => null]);

        $bidang = $this->bidangRepository->get_sektor_table($code);
        return view('bidangs.ekonomi.show')->with('code', $code)->with('bidang', $bidang);
    }

    // public function ekonomi_childs($id, BidangEkonomiChildsDataTable $dataTable)
    // {

    //     $parent = $this->bidangRepository->findWithoutFail($id);
    //     if (empty($parent)) {
    //         Flash::error('Sektor/Bidang not found.');
    //         return redirect(route('sektor-bidang.ekonomi.index'));
    //     }

    //     $subparent = $this->bidangRepository->findWithoutFail($parent->id_parent);

    //     return $dataTable->setParents($id)->renders('bidangs.ekonomi.index', [], [], ['parent' => $parent, 'subparent' => @$subparent]);
    // }

    public function ekonomi_create($id, Request $request)
    {
        $akses = Auth::user();
        if (!$akses->can('bidang-create')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $parent = $this->bidangRepository->findWithoutFail($id);
        if (empty($parent)) {
            Flash::error('Sektor/Bidang not found.');
            return redirect(route('sektor-bidang.ekonomi.index'));
        }

        $tahun = $this->tahunRepository->select('kode as tahun')->whereNull('id_parent')->orderBy('kode', 'asc')->pluck('tahun');
        $satuan = $this->satuanRepository->getArrayData();
        $bahasa = $this->bahasaRepository->all();
        $note = $this->noteRepository->getArrayData();
        $sumber = $this->sumberRepository->getArrayData();
        $segment = 'ekonomi'; //ucfirst((strtolower($request->segment(2))));
        if (strtolower($parent->nama) == $segment) {
            $parent->kode = '02.00.00.00.00.00';
        }

        return view('bidangs.create')->with('parent', $parent)
            ->with('segment', $segment)
            ->with('bahasa', $bahasa)
            ->with('satuan', $satuan)
            ->with('note', $note)
            ->with('sumber', $sumber)
            ->with('tahun', $tahun)
            ->with('id', $id);
    }

    public function statistik_index()
    {
        $akses = Auth::user();
        if (!$akses->can('bidang-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $code = '3';
        // $parent = $this->bidangRepository->where('kode', $code)->first();

        // return $dataTable->setParents($parent->id)->renders('bidangs.statistik.index', [], [], ['parent' => $parent, 'subparent' => null]);

        $bidang = $this->bidangRepository->get_sektor_table($code);
        return view('bidangs.statistik.show')->with('code', $code)->with('bidang', $bidang);
    }

    public function statistik_create($id, Request $request)
    {
        $akses = Auth::user();
        if (!$akses->can('bidang-create')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $parent = $this->bidangRepository->findWithoutFail($id);
        if (empty($parent)) {
            Flash::error('Sektor/Bidang not found.');
            return redirect(route('sektor-bidang.statistik.index'));
        }

        $satuan = $this->satuanRepository->getArrayData();
        $bahasa = $this->bahasaRepository->all();
        $note = $this->noteRepository->getArrayData();
        $sumber = $this->sumberRepository->getArrayData();
        $subparent = $this->bidangRepository->findWithoutFail($parent->id_parent);
        $segment = 'statistik'; //ucfirst((strtolower($request->segment(2))));
        if (strtolower($parent->nama) == $segment) {
            $parent->kode = '03.00.00.00.00.00';
        }
        $bidang_selevel = $this->bidangRepository->where('id_parent', $id)->get();
        return view('bidangs.statistik.create')->with('parent', $parent)
            ->with('subparent', $subparent)
            ->with('segment', $segment)
            ->with('bahasa', $bahasa)
            ->with('satuan', $satuan)
            ->with('note', $note)
            ->with('sumber', $sumber)
            ->with('bidang_selevel', $bidang_selevel)
            ->with('id', $id);
    }

    public function statistik_store(CreateBidangRequest $request)
    {
        $input = $request->all();

        $parent = $this->bidangRepository->findWithoutFail($input["id_parent"]);
        if (empty($parent)) {
            Flash::error('Sektor/Bidang Statistik not found.');
            return redirect(route('sektor-bidang.keuangan.index'));
        }

        // Cek Kode
        $cek_kode = $this->bidangRepository->where('kode', @$input["kode"])->first();
        if (!empty($cek_kode)) {
            return redirect()->back()->withInput($input)->withErrors(['message' => 'Kode ' . $input["kode"] . ' Sudah digunakan']);
        }

        // cek increament
        $exp = explode(".", @$input["kode"]);
        $increament = null;
        if (count($exp) > 1) {
            foreach ($exp as $no) {
                if (intval($no) > 0) $increament = intval($no);
            }
        }
        $input["id_increament"] = $increament;

        $cek = $this->bidangRepository->where('id_parent', $parent->id)->where('id_increament', @$increament)->first();
        if (!empty($cek)) {
            // return redirect()->back()->withInput($input)->withErrors(['message' => 'Kode ' . $input["kode"] . ' Sudah digunakan']);
        }

        $input["level"] = @$parent->level + 1;
        $input['id_sumber'] = @$input['id_sumber'] ? implode(',', @$input['id_sumber']) : null;
        $input['id_notes'] = @$input['id_notes'] ? implode(',', @$input['id_notes']) : null;
        $bidang = $this->bidangRepository->create($input);

        if ($input['root_selection'] != $bidang->root_selection) {
            $this->bidangRepository->set_root($bidang->id, $input['root_selection']);
        }

        Flash::success('Sektor/Bidang Statistik saved successfully.');
        return redirect(route('sektor-bidang.statistik.index'));
    }

    public function statistik_grouping(Request $request)
    {
        $bidang = $this->bidangRepository->get_sektor_statistik();
        return view('bidangs.statistik.group')
            ->with('bidang', $bidang);
    }

    public function statistik_form_grouping($id, Request $request)
    {
        $akses = Auth::user();
        if (!$akses->can('bidang-edit')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        $parent = $this->bidangRepository->findWithoutFail($id);
        if (empty($parent)) {
            Flash::error('Sektor/Bidang not found.');
            return redirect(route('sektor-bidang.statistik.index'));
        }

        $bidang = $this->bidangRepository->get_sektorgroup_statistik($id);
        return view('bidangs.statistik.form_group')
            ->with('parent', $parent)
            ->with('bidang', $bidang);
    }

    public function statistik_store_grouping(Request $request)
    {
        $input = $request->all();
        if (count(@$input['id']) > 0) {
            foreach ($input['id'] as $k => $v) {
                $flaging = '[' . $input['code'][$k] . ']';
                $this->bidangRepository->update(['tahun_nomenklatur' => $flaging], $v);
            }
        }
        Flash::success('Sektor/Bidang Statistik saved successfully.');
        return redirect(route('sektor-bidang.statistik.grouping'));
    }

    public function statistik_edit($id)
    {

        $akses = Auth::user();
        if (!$akses->can('bidang-edit')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $bidang = $this->bidangRepository->findWithoutFail($id);

        if (empty($bidang)) {
            Flash::error('Sektor/Bidang not found');
            return redirect(route('sektor-bidang.statistik.index'));
        }

        $parent = @$this->bidangRepository->findWithoutFail($bidang->id_parent);
        $firstParent = getFirstBidang($id);

        if (empty($firstParent)) {
            Flash::error('Sektor/Bidang not found');
            return redirect(route('sektor-bidang.statistik.index'));
        }

        // if (strtolower(@$firstParent['nama']) !== 'statistik') {
        //     Flash::error('Invalid Sektor/Bidang Statistik');
        //     return redirect(route('sektor-bidang.statistik.index'));
        // }

        if (strtolower($parent->nama) == 'statistik') {
            $parent->kode = '03.00.00.00.00.00';
        }

        $bahasa = $this->bahasaRepository->all();
        $note = $this->noteRepository->getArrayData();
        $sumber = $this->sumberRepository->getArrayData();
        $satuan = $this->satuanRepository->getArrayData();

        $bidang->tahun_nomenklatur = ltrim($bidang->tahun_nomenklatur, '[');
        $bidang->tahun_nomenklatur = rtrim($bidang->tahun_nomenklatur, ']');
        $bidang->tahun_nomenklatur = explode(',', $bidang->tahun_nomenklatur);
        $bidang->id_sumber = explode(',', $bidang->id_sumber);
        $bidang->id_notes = explode(',', $bidang->id_notes);

        return view('bidangs.statistik.edit')
            ->with('bidang', $bidang)
            ->with('bahasa', $bahasa)
            ->with('parent', $parent)
            ->with('satuan', $satuan)
            ->with('sumber', $sumber)
            ->with('note', $note);
    }

    /**
     * Update the specified Bidang in storage.
     *
     * @param  int              $id
     * @param UpdateBidangRequest $request
     *
     * @return Response
     */
    public function statistik_update($id, UpdateBidangRequest $request)
    {
        $input = $request->all();

        $bidang = $this->bidangRepository->findWithoutFail($id);

        if (empty($bidang)) {
            Flash::error('Sektor/Bidang not found.');
            return redirect(route('sektor-bidang.statistik.index'));
        }

        $parent = $this->bidangRepository->findWithoutFail($input["id_parent"]);
        $firstParent = $this->bidangRepository->get_first_sektor($id);

        if (empty($parent)) {
            Flash::error('Sektor/Bidang not found.');
            return redirect(route('sektor-bidang.statistik.index'));
        }

        if (strtolower(@$firstParent["nama"]) !== 'statistik') {
            Flash::error('Invalid Sektor/Bidang Statistik');
            return redirect(route('sektor-bidang.statistik.index'));
        }

        // Cek Kode lama
        if ($bidang->kode != $input["kode"]) {
            // Cek Kode
            $cek_kode = $this->bidangRepository->where('kode', @$input["kode"])->first();
            if (!empty($cek_kode)) {
                return redirect()->back()->withInput($input)->withErrors(['message' => 'Kode ' . $input["kode"] . ' Sudah digunakan']);
            }

            $exp = explode(".", @$input["kode"]);
            $increament = null;
            if (count($exp) > 1) {
                foreach ($exp as $no) {
                    if (intval($no) > 0) $increament = intval($no);
                }
            }
            $input["id_increament"] = $increament;
        }

        $input["level"] = @$parent->level + 1;
        $input['id_sumber'] = @$input['id_sumber'] ? implode(',', @$input['id_sumber']) : null;
        $input['id_notes'] = @$input['id_notes'] ? implode(',', @$input['id_notes']) : null;

        $bidang = $this->bidangRepository->update($input, $id);


        if ($input['root_selection'] != $bidang->root_selection) {
            $this->bidangRepository->set_root($bidang->id, $input['root_selection']);
        }


        Flash::success('Sektor/Bidang statistik updated successfully.');

        return redirect(route('sektor-bidang.statistik.index'));
    }

    /**
     * Store a newly created Bidang in storage.
     *
     * @param CreateBidangRequest $request
     *
     * @return Response
     */
    public function store(CreateBidangRequest $request)
    {
        $input = $request->all();
        $segment = @$input["segment"]; //ucfirst(strtolower(@$input["segment"]));

        $parent = $this->bidangRepository->findWithoutFail($input["id_parent"]);
        if (empty($parent)) {
            Flash::error('Sektor/Bidang ' . $segment . ' not found.');
            return redirect(route('sektor-bidang.keuangan.index'));
        }

        // Cek Kode
        $cek_kode = $this->bidangRepository->where('kode', @$input["kode"])->first();
        if (!empty($cek_kode)) {
            return redirect()->back()->withInput($input)->withErrors(['message' => 'Kode ' . $input["kode"] . ' Sudah digunakan']);
        }

        // cek increament
        $exp = explode(".", @$input["kode"]);
        $increament = null;
        if (count($exp) > 1) {
            foreach ($exp as $no) {
                if (intval($no) > 0) $increament = intval($no);
            }
        }
        $input["id_increament"] = $increament;

        $cek = $this->bidangRepository->where('id_parent', $parent->id)->where('id_increament', @$increament)->first();
        if (!empty($cek)) {
            // return redirect()->back()->withInput($input)->withErrors(['message' => 'Kode ' . $input["kode"] . ' Sudah digunakan']);
        }

        if (count($input["tahun_nomenklatur"]) > 0) {
            $input["tahun_nomenklatur"] = '[' . implode(',', @$input["tahun_nomenklatur"]) . ']';
        } else {
            $input["tahun_nomenklatur"] = NULL;
        }

        $input["level"] = @$parent->level + 1;
        $input['id_sumber'] = @$input['id_sumber'] ? implode(',', @$input['id_sumber']) : null;
        $input['id_notes'] = @$input['id_notes'] ? implode(',', @$input['id_notes']) : null;

        $bidang = $this->bidangRepository->create($input);

        if ($input['root_selection'] != $bidang->root_selection) {
            $this->bidangRepository->set_root($bidang->id, $input['root_selection']);
        }

        Flash::success('Sektor/Bidang ' . $segment . ' saved successfully.');

        if (strtolower(@$segment) == 'keuangan') {
            return redirect(route('sektor-bidang.keuangan.index'));
        } elseif (strtolower(@$segment) == 'ekonomi') {
            return redirect(route('sektor-bidang.ekonomi.index'));
        } elseif (strtolower(@$segment) == 'statistik') {
            return redirect(route('sektor-bidang.statistik.index'));
        }
    }

    /**
     * Show the form for editing the specified Bidang.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id, Request $request)
    {
        $akses = Auth::user();
        if (!$akses->can('bidang-edit')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $segment = ucfirst((strtolower($request->segment(2))));
        $bidang = $this->bidangRepository->findWithoutFail($id);

        if (empty($bidang)) {
            Flash::error('Sektor/Bidang not found');
            return redirect(route('sektor-bidang.' . strtolower($segment) . '.index'));
        }

        $parent = @$this->bidangRepository->findWithoutFail($bidang->id_parent);
        $firstParent = getFirstBidang($id);

        if (empty($firstParent)) {
            Flash::error('Sektor/Bidang not found');
            return redirect(route('sektor-bidang.' . strtolower($segment) . '.index'));
        }

        if (strtolower(@$firstParent['name']) !== strtolower($segment)) {
            Flash::error('Invalid Sektor/Bidang');
            return redirect(route('sektor-bidang.' . strtolower($segment) . '.index'));
        }
        if (strtolower($parent->nama) == 'keuangan') {
            $parent->kode = '01.00.00.00.00.00';
        } elseif (strtolower($parent->nama) == 'ekonomi') {
            $parent->kode = '02.00.00.00.00.00';
        } elseif (strtolower($parent->nama) == 'statistik') {
            $parent->kode = '03.00.00.00.00.00';
        }

        $tahun = $this->tahunRepository->select('kode as tahun')->whereNull('id_parent')->orderBy('kode', 'desc')->pluck('tahun');
        $bahasa = $this->bahasaRepository->all();
        $note = $this->noteRepository->getArrayData();
        $sumber = $this->sumberRepository->getArrayData();
        $satuan = $this->satuanRepository->getArrayData();

        $bidang->tahun_nomenklatur = ltrim($bidang->tahun_nomenklatur, '[');
        $bidang->tahun_nomenklatur = rtrim($bidang->tahun_nomenklatur, ']');
        $bidang->tahun_nomenklatur = explode(',', $bidang->tahun_nomenklatur);
        $bidang->id_sumber = explode(',', $bidang->id_sumber);
        $bidang->id_notes = explode(',', $bidang->id_notes);

        return view('bidangs.edit')
            ->with('bidang', $bidang)
            ->with('bahasa', $bahasa)
            ->with('parent', $parent)
            ->with('segment', $segment)
            ->with('satuan', $satuan)
            ->with('sumber', $sumber)
            ->with('tahun', $tahun)
            ->with('note', $note);
    }

    /**
     * Update the specified Bidang in storage.
     *
     * @param  int              $id
     * @param UpdateBidangRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $input = $request->all();

        $segment = ucfirst(strtolower(@$input["segment"]));
        $bidang = $this->bidangRepository->findWithoutFail($id);

        if (empty($bidang)) {
            Flash::error('Sektor/Bidang not found.');
            return redirect(route('sektor-bidang.' . strtolower($segment) . '.index'));
        }

        $parent = $this->bidangRepository->findWithoutFail($input["id_parent"]);
        $firstParent = $this->bidangRepository->get_first_sektor($id);

        if (empty($parent)) {
            Flash::error('Sektor/Bidang not found.');
            return redirect(route('sektor-bidang.' . strtolower($segment) . '.index'));
        }

        if (strtolower(@$firstParent['nama']) !== strtolower($segment)) {
            Flash::error('Invalid Sektor/Bidang ' . $segment);
            return redirect(route('sektor-bidang.' . strtolower($segment) . '.index'));
        }

        // Cek Kode lama
        if ($bidang->kode != $input["kode"]) {
            // Cek Kode
            $cek_kode = $this->bidangRepository->where('kode', @$input["kode"])->first();
            if (!empty($cek_kode)) {
                return redirect()->back()->withInput($input)->withErrors(['message' => 'Kode ' . $input["kode"] . ' Sudah digunakan']);
            }

            $exp = explode(".", @$input["kode"]);
            $increament = null;
            if (count($exp) > 1) {
                foreach ($exp as $no) {
                    if (intval($no) > 0) $increament = intval($no);
                }
            }
            $input["id_increament"] = $increament;
        }

        if (count($input["tahun_nomenklatur"]) > 0) {
            $input["tahun_nomenklatur"] = '[' . implode(',', @$input["tahun_nomenklatur"]) . ']';
        } else {
            $input["tahun_nomenklatur"] = NULL;
        }


        $input["level"] = @$parent->level + 1;
        $input['id_sumber'] = @$input['id_sumber'] ? implode(',', @$input['id_sumber']) : null;
        $input['id_notes'] = @$input['id_notes'] ? implode(',', @$input['id_notes']) : null;

        $bidang = $this->bidangRepository->update($input, $id);

        if ($input['root_selection'] != $bidang->root_selection) {
            $this->bidangRepository->set_root($bidang->id, $input['root_selection']);
        }

        Flash::success('Sektor/Bidang ' . $segment . ' updated successfully.');

        if (strtolower(@$segment) == 'keuangan') {
            return redirect(route('sektor-bidang.keuangan.index'));
        } elseif (strtolower(@$segment) == 'ekonomi') {
            return redirect(route('sektor-bidang.ekonomi.index'));
        } elseif (strtolower(@$segment) == 'statistik') {
            return redirect(route('sektor-bidang.statistik.index'));
        }
    }

    /**
     * Display the specified Bidang.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id, Request $request)
    {
        $akses = Auth::user();
        if (!$akses->can('bidang-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $segment = ucfirst((strtolower($request->segment(2))));
        $bidang = $this->bidangRepository->findWithoutFail($id);

        if (empty($bidang)) {
            Flash::error('Sektor/Bidang not found');
            return redirect(route('sektor-bidang.' . strtolower($segment) . '.index'));
        }

        if (strtolower(@$bidang->nama) !== strtolower($segment)) {
            Flash::error('Invalid Sektor/Bidang');
            return redirect(route('sektor-bidang.' . strtolower($segment) . '.index'));
        }

        $data = $this->show_trees_bidang($id);

        return view('bidangs.show')->with('bidang', $bidang)->with('data', $data)->with('segment', $segment);
    }

    public function destroy($id, Request $request)
    {
        $segment = ucfirst((strtolower($request->segment(2))));

        $bidang = $this->bidangRepository->findWithoutFail($id);
        if (empty($bidang)) {
            Flash::error('Sektor/Bidang not found.');
            return redirect(route('sektor-bidang.' . strtolower(@$segment) . '.index'));
        }

        $firstParent = getFirstBidang($id);
        $parent = $this->bidangRepository->findWithoutFail($bidang->id_parent);
        $subparent = $this->bidangRepository->findWithoutFail($parent->id_parent);


        if (empty($parent)) {
            Flash::error('Sektor/Bidang not found.');
            return redirect(route('sektor-bidang.' . strtolower($segment) . '.index'));
        }

        if (strtolower(@$firstParent['name']) !== strtolower($segment)) {
            Flash::error('Invalid Sektor/Bidang ' . strtolower($segment));
            return redirect(route('sektor-bidang.' . strtolower($segment) . '.index'));
        }

        // cari tahun nomenklatur
        $tahun = $this->tahunRepository->all()->pluck('tahun')->toArray();
        // hapus nomenklatur_amount
        if (count($tahun) > 0) {
            foreach ($tahun as $thn) {
                if (Schema::hasTable('nomenklatur_amount_' . $thn)) {
                    DB::table('nomenklatur_amount_' . $thn)->where('id_bidang', $id)->delete();
                }
            }
        }
        // hapus sektor/bidang
        $bidang = $this->bidangRepository->whereId($id)->forceDelete();

        Flash::success('Sektor/Bidang ' . $segment . ' delete successfully.');
        return redirect(route('sektor-bidang.' . strtolower($segment) . '.index'));
    }

    public function check_tree(int $id)
    {
        $result = $this->bidangRepository->check_tree_status($id);

        $result["message"] = "Success checking sector tree";

        return response()->json($result);
    }
}
