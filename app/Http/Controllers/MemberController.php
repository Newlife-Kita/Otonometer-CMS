<?php

namespace App\Http\Controllers;

use App\DataTables\LogMemberRegisterDataTable;
use App\DataTables\MemberDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Repositories\MemberRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class MemberController extends AppBaseController
{
    /** @var  MemberRepository */
    private $memberRepository;

    public function __construct(MemberRepository $memberRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:member-edit', ['only' => ['edit']]);
        $this->middleware('can:member-store', ['only' => ['store']]);
        $this->middleware('can:member-show', ['only' => ['show']]);
        $this->middleware('can:member-update', ['only' => ['update']]);
        $this->middleware('can:member-delete', ['only' => ['delete']]);
        $this->middleware('can:member-create', ['only' => ['create']]);
        $this->memberRepository = $memberRepo;
    }

    /**
     * Display a listing of the Member.
     *
     * @param MemberDataTable $memberDataTable
     * @return Response
     */
    public function index(MemberDataTable $memberDataTable)
    {
        return $memberDataTable->render('members.index');
    }

    public function logRegister()
    {
        return view('members.regis_log.index');
    }

    public function ajaxLogRegister()
    {
        $logData = $this->queryRegisterLog();
        return response()->json(['valid' => true, 'items' => $logData, 'message' => 'success']);
    }

    private function queryRegisterLog()
    {
        $results = DB::select("
        SELECT 
            dates.date,
            COUNT(member.created_at) AS member_count
        FROM (
            SELECT 
                CURDATE() - INTERVAL (a.a + (10 * b.a) + (100 * c.a) + (1000 * d.a)) DAY AS date
            FROM 
                (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
            CROSS JOIN 
                (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
            CROSS JOIN 
                (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) c
            CROSS JOIN 
                (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) d
        ) AS dates
        LEFT JOIN member ON DATE(member.created_at) = dates.date
        WHERE dates.date BETWEEN (SELECT MIN(DATE(created_at)) FROM member) AND CURDATE()
        GROUP BY dates.date
        ORDER BY dates.date
    ");

        return $results;
    }

    /**
     * Show the form for creating a new Member.
     *
     * @return Response
     */
    public function create()
    {
        return redirect(route('members.index'));
        return view('members.create');
    }

    /**
     * Store a newly created Member in storage.
     *
     * @param CreateMemberRequest $request
     *
     * @return Response
     */
    public function store(CreateMemberRequest $request)
    {
        return redirect(route('members.index'));

        $input = $request->all();

        $member = $this->memberRepository->create($input);

        Flash::success('Member saved successfully.');
        return redirect(route('members.index'));
    }

    /**
     * Display the specified Member.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $member = $this->memberRepository->findWithoutFail($id);

        if (empty($member)) {
            Flash::error('Member not found');
            return redirect(route('members.index'));
        }

        return view('members.show')->with('member', $member);
    }

    /**
     * Show the form for editing the specified Member.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        return redirect(route('members.index'));
        $member = $this->memberRepository->findWithoutFail($id);

        if (empty($member)) {
            Flash::error('Member not found');
            return redirect(route('members.index'));
        }

        return view('members.edit')
            ->with('member', $member);
    }

    /**
     * Update the specified Member in storage.
     *
     * @param  int              $id
     * @param UpdateMemberRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMemberRequest $request)
    {
        $member = $this->memberRepository->findWithoutFail($id);

        if (empty($member)) {
            Flash::error('Member not found');
            return redirect(route('members.index'));
        }

        $input = $request->all();
        $member = $this->memberRepository->update($input, $id);

        Flash::success('Member updated successfully.');
        return redirect(route('members.index'));
    }

    /**
     * Remove the specified Member from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $member = $this->memberRepository->findWithoutFail($id);

        if (empty($member)) {
            Flash::error('Member not found');
            return redirect(route('members.index'));
        }

        $this->memberRepository->delete($id);

        Flash::success('Member deleted successfully.');
        return redirect(route('members.index'));
    }

    /**
     * Store data Member from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function ($reader) {
            $reader->each(function ($item) {
                $member = $this->memberRepository->create($item->toArray());
            });
        });

        Flash::success('Member saved successfully.');
        return redirect(route('members.index'));
    }
}
