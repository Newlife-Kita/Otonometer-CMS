<?php

namespace App\Http\Controllers;

use App\DataTables\SettingDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Repositories\SettingRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BahasaRepository;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SettingController extends AppBaseController
{
    /** @var  SettingRepository */
    private $settingRepository;
    private $bahasaRepopsitory;

    public function __construct(SettingRepository $settingRepo, BahasaRepository $bahasaRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:setting-edit', ['only' => ['edit']]);
        $this->middleware('can:setting-store', ['only' => ['store']]);
        $this->middleware('can:setting-show', ['only' => ['show']]);
        $this->middleware('can:setting-update', ['only' => ['update']]);
        $this->middleware('can:setting-delete', ['only' => ['delete']]);
        $this->middleware('can:setting-create', ['only' => ['create']]);
        $this->settingRepository = $settingRepo;
        $this->bahasaRepopsitory = $bahasaRepo;
    }

    /**
     * Display a listing of the Setting.
     *
     * @param SettingDataTable $settingDataTable
     * @return Response
     */
    public function index(SettingDataTable $settingDataTable)
    {
        return $settingDataTable->render('settings.index');
    }

    /**
     * Show the form for creating a new Setting.
     *
     * @return Response
     */
    public function create()
    {
        $bahasa = $this->bahasaRepopsitory->all();
        return view('settings.create')->with('bahasa', $bahasa);
    }

    /**
     * Store a newly created Setting in storage.
     *
     * @param CreateSettingRequest $request
     *
     * @return Response
     */
    public function store(CreateSettingRequest $request)
    {
        $input = $request->all();

        $setting = $this->settingRepository->create($input);

        Flash::success('Setting saved successfully.');
        return redirect(route('settings.index'));
    }

    /**
     * Display the specified Setting.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $setting = $this->settingRepository->findWithoutFail($id);

        if (empty($setting)) {
            Flash::error('Setting not found');
            return redirect(route('settings.index'));
        }

        return view('settings.show')->with('setting', $setting);
    }

    /**
     * Show the form for editing the specified Setting.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {

        $setting = $this->settingRepository->findWithoutFail($id);

        if (empty($setting)) {
            Flash::error('Setting not found');
            return redirect(route('settings.index'));
        }

        $bahasa = $this->bahasaRepopsitory->all();

        return view('settings.edit')
            ->with('setting', $setting)
             ->with('bahasa', $bahasa);
    }

    /**
     * Update the specified Setting in storage.
     *
     * @param  int              $id
     * @param UpdateSettingRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSettingRequest $request)
    {
        $setting = $this->settingRepository->findWithoutFail($id);

        if (empty($setting)) {
            Flash::error('Setting not found');
            return redirect(route('settings.index'));
        }

        $input = $request->all();
        $setting = $this->settingRepository->update($input, $id);

        Flash::success('Setting updated successfully.');
        return redirect(route('settings.index'));
    }

    /**
     * Remove the specified Setting from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $setting = $this->settingRepository->findWithoutFail($id);

        if (empty($setting)) {
            Flash::error('Setting not found');
            return redirect(route('settings.index'));
        }

        $this->settingRepository->delete($id);

        Flash::success('Setting deleted successfully.');
        return redirect(route('settings.index'));
    }

    /**
     * Store data Setting from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function($reader) {
            $reader->each(function ($item) {
                $setting = $this->settingRepository->create($item->toArray());
            });
        });

        Flash::success('Setting saved successfully.');
        return redirect(route('settings.index'));
    }
}
