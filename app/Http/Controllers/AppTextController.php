<?php

namespace App\Http\Controllers;

use App\DataTables\AppTextDataTable;
use App\DataTables\NodeTextDataTable;
use App\Http\Requests\UpdateAppTextRequest;
use App\Repositories\AppTextRepository;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use App\Models\Bahasa;
use App\Repositories\AppStructureRepository;
use Response;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AppTextController extends AppBaseController
{
    /** @var  AppTextRepository */
    private $appTextRepository;
    private $appStructureRepository;

    public function __construct(AppTextRepository $appTextRepo, AppStructureRepository $appStructureRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:appText-edit', ['only' => ['edit']]);
        $this->middleware('can:appText-store', ['only' => ['store']]);
        $this->middleware('can:appText-show', ['only' => ['show']]);
        $this->middleware('can:appText-update', ['only' => ['update']]);
        $this->middleware('can:appText-delete', ['only' => ['delete']]);
        $this->middleware('can:appText-create', ['only' => ['create']]);
        $this->appTextRepository = $appTextRepo;
        $this->appStructureRepository = $appStructureRepo;
    }

    /**
     * Display a listing of the AppText.
     *
     * @param AppTextDataTable $appTextDataTable
     * @return Response
     */
    public function index(AppTextDataTable $appTextDataTable)
    {
        return $appTextDataTable->render('app_texts.index');
    }

    /**
     * Display a listing of the AppText.
     *
     * @param NodeTextDataTable $appTextDataTable
     * @return Response
     */
    public function node(NodeTextDataTable $nodeTextDataTable, $id)
    {
        $appStructure = $this->appStructureRepository->findWithoutFail($id);


        if (empty($appStructure)) {
            Flash::error('Parent is not found');
            return redirect()->back();
        }

        $breadcrumb = $this->appStructureRepository->getToRoot($id);

        return $nodeTextDataTable->setParentId($id)->render('app_texts.index', ['breadcrumb' => $breadcrumb]);
    }



    /**
     * Show the form for editing the specified AppText.
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

        $appText = $this->appTextRepository->where('text_id', $appStructure->id)->first();

        if (empty($appText)) {
            Flash::error('App Text not found');
            return redirect()->back();
        }

        $language = Bahasa::where('status', 'tampil')->get();

        return view('app_texts.edit')
            ->with('appText', $appText)
            ->with('language', $language)
            ;
    }

    /**
     * Update the specified AppText in storage.
     *
     * @param  int              $id
     * @param UpdateAppTextRequest $request
     *
     * @return Response
     */
    public function nodeUpdate($id, UpdateAppTextRequest $request)
    {
        $appText = $this->appTextRepository->findWithoutFail($id);


        if (empty($appText)) {
            Flash::error('App Text not found');
            return redirect(route('app-structure.page'));
        }

        $input = $request->all();
        $appText = $this->appTextRepository->update($input, $id);
        $appTextParent = $this->appStructureRepository->find($appText->text_id);
        Flash::success('App Text updated successfully.');
        return redirect(route('app-structure.page'));
    }
}
