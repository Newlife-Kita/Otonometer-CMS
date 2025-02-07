<?php

namespace App\Http\Controllers\Webcore;

use App\DataTables\AdminDataTable;
use App\Http\Requests;
use App\Http\Requests\Webcore\CreateUserRequest;
use App\Http\Requests\Webcore\UpdateUserRequest;
use App\Repositories\UserRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Models\Permission;
use App\Models\Permissiongroup;
use App\Models\Permissionlabel;
use App\Models\Wilayah;
use Response;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request; // added by dandisy
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth; // added by dandisy
use Illuminate\Support\Facades\Storage; // added by dandisy
use Maatwebsite\Excel\Facades\Excel; // added by dandisy

class AdminController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->middleware('auth');
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     *
     * @param UserDataTable $userDataTable
     * @return Response
     */
    public function index(AdminDataTable $userDataTable)
    {
        return $userDataTable->render('admins.index');
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        return view('admins.create');
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $input = $request->all();

        $user = $this->userRepository->create($input);
        // ROles untuk admin wilayah/daerah
        $role = Role::whereName('admin wilayah')->first();
        if ($role) {
            $user->roles()->attach(@$role->id);
        }
        
        Flash::success('Admin Wilayah/Daerah saved successfully.');
        return redirect(route('admin.index'));
    }

    /**
     * Ajax Wilayah
     *
     */
    public function show($id, Request $request)
    {
        $input = $request->all();
        $search = @$input['term'];
            
        $items = Wilayah::where(function($query) use ($search){
            return $query->where('nama', 'like', '%'.$search.'%')->orWhere('alamat_kantor_pemerintahan', 'like', '%'.$search.'%');
            // ->orWhere('kodepos', 'like', '%'.$search.'%');
        })  
        ->selectRaw('id, nama, tipe, alamat_kantor_pemerintahan')         
        ->get();
        return response()->json(['items' => $items->toArray()]); 
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('Admin Wilayah/Daerah Not Found');
            return redirect(route('users.index'));
        }

        $wilayah = Wilayah::find($user->id_wilayah);
        return view('admins.edit')
            ->with('wilayah', $wilayah)
            ->with('user', $user);
    }

    /**
     * Update the specified User in storage.
     *
     * @param  int              $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('Admin Wilayah/Daerah Not Found');

            return redirect(route('users.index'));
        }

        $role = Role::whereName('admin wilayah')->first();
        if ($role) {
            $user->syncRoles(@$role->id);
        }
        else {
            $user->roles()->detach();
        }
        $user = $this->userRepository->update($request->all(), $id);
        Flash::success('Admin Wilayah/Daerah updated successfully.');
        return redirect(route('admin.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('Admin Wilayah/Daerah Not Found');
            return redirect(route('admin.index'));
        }
        $user->roles()->detach();
        $this->userRepository->delete($id);
        Flash::success('Admin Wilayah/Daerah deleted successfully.');
        return redirect(route('admin.index'));
    }
}
