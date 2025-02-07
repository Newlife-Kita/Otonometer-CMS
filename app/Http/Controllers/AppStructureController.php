<?php

namespace App\Http\Controllers;

use App\DataTables\AppStructureDataTable;
use App\DataTables\NodeDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateAppStructureRequest;
use App\Http\Requests\UpdateAppStructureRequest;
use App\Repositories\AppStructureRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Repositories\AppTextRepository;
use App\Services\UploadFileService;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class AppStructureController extends AppBaseController
{
    /** @var  AppStructureRepository */
    private $appStructureRepository;
    private $appTextRepository;
    private $uploadFile;
    private $path = "app-struct";

    public function __construct(AppStructureRepository $appStructureRepo, AppTextRepository $appTextRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:appStructure-edit', ['only' => ['edit']]);
        $this->middleware('can:appStructure-store', ['only' => ['store']]);
        $this->middleware('can:appStructure-show', ['only' => ['show']]);
        $this->middleware('can:appStructure-update', ['only' => ['update']]);
        $this->middleware('can:appStructure-delete', ['only' => ['delete']]);
        $this->middleware('can:appStructure-create', ['only' => ['create']]);
        $this->appStructureRepository = $appStructureRepo;
        $this->appTextRepository = $appTextRepo;

        $this->uploadFile = new UploadFileService();
    }

    /**
     * Display a listing of the AppStructure.
     *
     * @param AppStructureDataTable $appStructureDataTable
     * @return Response
     */
    public function index(AppStructureDataTable $appStructureDataTable)
    {
        return $appStructureDataTable->render('app_structures.page.index');
    }


    /**
     * Display the tree of the AppStructure.
     * 
     * @return Response
     */
    public function tree()
    {
        $appStructure = $this->appStructureRepository->get_sektor_table(null);
        return view('app_structures.tree')->with('bidang', $appStructure);
    }

    /**
     * Show the form for creating a new page in app.
     *
     * @return Response
     */
    public function pageCreate()
    {
        return view('app_structures.page.create');
    }

    /**
     * Store a newly created page.
     *
     * @param CreateAppStructureRequest $request
     *
     * @return Response
     */
    public function pageStore(CreateAppStructureRequest $request)
    {
        $input = $request->all();
        $file = $request->file('image');
        $input['image'] = $this->uploadFile->uploadFile($this->path, 'page', $file);

        $appStructure = $this->appStructureRepository->create($input);

        Flash::success('App Structure saved successfully.');
        return redirect(route('app-structure.page'));
    }

    /**
     * Display the specified AppStructure.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function node(int $id, NodeDataTable $nodeDataTable)
    {
        $appStructure = $this->appStructureRepository->findWithoutFail($id);

        if (empty($appStructure)) {
            Flash::error('Parent is not found');
            return redirect()->back();
        }

        $breadcrumb = $this->appStructureRepository->getToRoot($id);

        return $nodeDataTable->setParentNodeId($id)->render('app_structures.node.index', ["parent" => $appStructure, "breadcrumb" =>$breadcrumb]);
    }

    /**
     * Show the form for creating a new node in app.
     *
     * @return Response
     */
    public function nodeCreate(int $id)
    {
        return view('app_structures.node.create')->with(['id' => $id]);
    }

    /**
     * Store a newly created page.
     *
     * @param CreateAppStructureRequest $request
     *
     * @return Response
     */
    public function nodeStore(CreateAppStructureRequest $request, int $id)
    {
        $input = $request->all();
        $file = $request->file('image');
        $input['image'] = $this->uploadFile->uploadFile($this->path, 'page', $file);

        $appStructure = $this->appStructureRepository->create($input);

        if ($appStructure->type = "text") {
            $this->appTextRepository->create(["text_id" => $appStructure->id]);
        }


        Flash::success('App Structure saved successfully.');
        return redirect(route('app-structure.page'));
    }



    /**
     * Show the form for editing the specified AppStructure.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function pageEdit($id)
    {

        $appStructure = $this->appStructureRepository->findWithoutFail($id);

        if (empty($appStructure)) {
            Flash::error('App Structure not found');
            return redirect(route('appStructures.index'));
        }

        return view('app_structures.page.edit')
            ->with('data', $appStructure);
    }

    /**
     * Update the specified AppStructure in storage.
     *
     * @param  int              $id
     * @param UpdateAppStructureRequest $request
     *
     * @return Response
     */
    public function pageUpdate($id, UpdateAppStructureRequest $request)
    {
        $appStructure = $this->appStructureRepository->findWithoutFail($id);

        if (empty($appStructure)) {
            Flash::error('App Structure not found');
            return redirect(route('app-structure.page'));
        }

        $input = $request->all();
        $file = $request->file('image');
        $input['image'] = $this->uploadFile->uploadFile($this->path, 'page', $file);

        $appStructure = $this->appStructureRepository->update($input, $id);

        Flash::success('App Structure updated successfully.');
        return redirect(route('app-structure.page'));
    }

    /**
     * Remove the specified AppStructure from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function pageDestroy($id)
    {
        $appStructure = $this->appStructureRepository->findWithoutFail($id);

        if (empty($appStructure)) {
            Flash::error('App Structure not found');
            return redirect(route('app-structure.page'));
        }

        $this->appStructureRepository->delete($id);

        Flash::success('App Structure deleted successfully.');
        return redirect(route('app-structure.page'));
    }

    /**
     * Show the form for editing the specified AppStructure.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function nodeEdit($id)
    {

        $appStructure = $this->appStructureRepository->findWithoutFail($id);

        if (empty($appStructure)) {
            Flash::error('App Structure not found');
            return redirect()->back();
        }

        return view('app_structures.node.edit')
            ->with('data', $appStructure);
    }

    /**
     * Update the specified AppStructure in storage.
     *
     * @param  int              $id
     * @param UpdateAppStructureRequest $request
     *
     * @return Response
     */
    public function nodeUpdate($id, UpdateAppStructureRequest $request)
    {
        $appStructure = $this->appStructureRepository->findWithoutFail($id);

        if (empty($appStructure)) {
            Flash::error('App Structure not found');
            return redirect(route('app-structure.node.edit', $id));
        }
        $input = $request->all();
        $file = $request->file('image');
        $input['image'] = $this->uploadFile->uploadFile($this->path, 'node', $file);
        $appStructure = $this->appStructureRepository->update($input, $id);

        if ($appStructure->type == "node") {
            $appText = $this->appStructureRepository->where('parent_node_id', $id)->first();
            if ($appText) {
                $this->appTextRepository->delete($appText->id);
            }
        }

        Flash::success('App Structure updated successfully.');
        return redirect(route('app-structure.node', $appStructure->parent_node_id));
    }

    /**
     * Remove the specified AppStructure from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function nodeDestroy($id)
    {
        $appStructure = $this->appStructureRepository->findWithoutFail($id);

        if (empty($appStructure)) {
            Flash::error('App Structure not found');
            return redirect()->back();
        }

        $parentId = $appStructure->parent_node_id;

        $this->appStructureRepository->delete($id);

        Flash::success('App Structure deleted successfully.');
        return redirect(route('app-structure.node', $parentId));
    }
}
