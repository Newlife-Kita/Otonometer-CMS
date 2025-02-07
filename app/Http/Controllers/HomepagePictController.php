<?php

namespace App\Http\Controllers;

use App\DataTables\HomepagePictDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateHomepagePictRequest;
use App\Http\Requests\UpdateHomepagePictRequest;
use App\Repositories\HomepagePictRepository;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use App\Services\UploadCustomNameService;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class HomepagePictController extends AppBaseController
{
    /** @var  HomepagePictRepository */
    private $homepagePictRepository;

    /** @var  UploadCustomNameService */
    private $uploadFileService;

    /** @var string */
    private $path = 'homepage_picts/';

    public function __construct(
        HomepagePictRepository $homepagePictRepo,
        UploadCustomNameService $uploadFileService
    ) {
        $this->middleware('auth');
        $this->middleware('can:appText-edit', ['only' => ['edit']]);
        $this->middleware('can:appText-store', ['only' => ['store']]);
        $this->middleware('can:appText-show', ['only' => ['show']]);
        $this->middleware('can:appText-update', ['only' => ['update']]);
        $this->middleware('can:appText-delete', ['only' => ['delete']]);
        $this->middleware('can:appText-create', ['only' => ['create']]);
        $this->homepagePictRepository = $homepagePictRepo;
        $this->uploadFileService = $uploadFileService;
    }

    /**
     * Display a listing of the HomepagePict.
     *
     * @param HomepagePictDataTable $homepagePictDataTable
     * @return Response
     */
    public function index(HomepagePictDataTable $homepagePictDataTable)
    {
        return $homepagePictDataTable->render('homepage_picts.index');
    }

    /**
     * Show the form for creating a new HomepagePict.
     *
     * @return Response
     */
    public function create()
    {


        return view('homepage_picts.create');
    }

    /**
     * Store a newly created HomepagePict in storage.
     *
     * @param CreateHomepagePictRequest $request
     *
     * @return Response
     */
    public function store(CreateHomepagePictRequest $request)
    {
        $input = $request->all();

        if ($request->hasFile('link_light_mode')) {
            $input['link_light_mode'] = $this->uploadFileService->uploadFile($this->path, $input['name'].' light_mode', 'link_light_mode', $request->file('link_light_mode'));
        }

        if ($request->hasFile('link_dark_mode')) {
            $input['link_dark_mode'] = $this->uploadFileService->uploadFile($this->path, $input['name'].' dark_mode', 'link_dark_mode', $request->file('link_dark_mode'));
        }

        $homepagePict = $this->homepagePictRepository->create($input);

        Flash::success('Homepage Pict saved successfully.');
        return redirect(route('homepagePicts.index'));
    }

    /**
     * Display the specified HomepagePict.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $homepagePict = $this->homepagePictRepository->findWithoutFail($id);

        if (empty($homepagePict)) {
            Flash::error('Homepage Pict not found');
            return redirect(route('homepagePicts.index'));
        }

        return view('homepage_picts.show')->with('homepagePict', $homepagePict);
    }

    /**
     * Show the form for editing the specified HomepagePict.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {



        $homepagePict = $this->homepagePictRepository->findWithoutFail($id);

        if (empty($homepagePict)) {
            Flash::error('Homepage Pict not found');
            return redirect(route('homepagePicts.index'));
        }

        return view('homepage_picts.edit')
            ->with('homepagePict', $homepagePict);
    }

    /**
     * Update the specified HomepagePict in storage.
     *
     * @param  int              $id
     * @param UpdateHomepagePictRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateHomepagePictRequest $request)
    {
        $homepagePict = $this->homepagePictRepository->findWithoutFail($id);

        if (empty($homepagePict)) {
            Flash::error('Homepage Pict not found');
            return redirect(route('homepagePicts.index'));
        }

        $input = $request->all();

        if ($request->hasFile('link_light_mode')) {
            $input['link_light_mode'] = $this->uploadFileService->uploadFile($this->path, $input['name'].' light_mode', 'link_light_mode', $request->file('link_light_mode'), $homepagePict, 'update');
        }else{
            $input['link_light_mode'] = $homepagePict->link_light_mode;
        }

        if ($request->hasFile('link_dark_mode')) {
            $input['link_dark_mode'] = $this->uploadFileService->uploadFile($this->path, $input['name'].' dark_mode', 'link_dark_mode', $request->file('link_dark_mode'), $homepagePict, 'update');
        }else{
            $input['link_dark_mode'] = $homepagePict->link_dark_mode;
        }
        $homepagePict = $this->homepagePictRepository->update($input, $id);

        Flash::success('Homepage Pict updated successfully.');
        return redirect(route('homepagePicts.index'));
    }

    /**
     * Remove the specified HomepagePict from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $homepagePict = $this->homepagePictRepository->findWithoutFail($id);

        if (empty($homepagePict)) {
            Flash::error('Homepage Pict not found');
            return redirect(route('homepagePicts.index'));
        }

        $this->homepagePictRepository->delete($id);

        Flash::success('Homepage Pict deleted successfully.');
        return redirect(route('homepagePicts.index'));
    }

    /**
     * Store data HomepagePict from an excel file in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        Excel::load($request->file('file'), function ($reader) {
            $reader->each(function ($item) {
                $homepagePict = $this->homepagePictRepository->create($item->toArray());
            });
        });

        Flash::success('Homepage Pict saved successfully.');
        return redirect(route('homepagePicts.index'));
    }
}
