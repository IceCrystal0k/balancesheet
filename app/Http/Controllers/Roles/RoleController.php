<?php

namespace App\Http\Controllers\Roles;

use App\Helpers\ExportUtils;
use App\Helpers\Form;
use App\Helpers\HtmlControls;
use App\Helpers\UserUtils;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use PDF;
use Yajra\Datatables\Datatables;

/**
 * class which handles the list / create / update / delete of roles
 */
class RoleController extends Controller
{
    protected $viewPath = 'roles';
    protected $routePath = 'roles';
    protected $translationPrefix = 'role.';
    protected $model = 'App\Models\Role';
    private $updateFields; // fields that will be updated on save
    private $selectFields;
    private $userSettings;

    public function __construct()
    {
        $this->updateFields = ['name', 'slug'];
        $this->selectFields = ['id', 'name', 'slug'];

        $this->middleware(function ($request, $next) {
            $this->userSettings = UserUtils::getUserSetting(auth()->user()->id);
            return $next($request);
        });
    }

    /**
     * role list index
     * @return {view} list view
     */
    public function index()
    {
        $page = (object) ['title' => __($this->translationPrefix . 'Roles'), 'route' => '', 'routeCreate' => route($this->routePath . '/create'),
            'routePath' => $this->routePath, 'translationPrefix' => $this->translationPrefix, 'viewPath' => $this->viewPath];
        $breadcrumbPath = $this->routePath;
        return view($this->viewPath . '.list', compact('page', 'breadcrumbPath'));
    }

    /**
     * role list for datatable (ajax call)
     * @param {object} $request http request
     * @return {array} array with data for table
     */
    function list(Request $request) {
        $data = $this->model::select($this->selectFields);
        return Datatables::of($data)
            ->addColumn('updated_at', function ($item) {
                return date($this->userSettings->date_format_php, strtotime($item->updated_at));
            })
            ->addColumn('actions', function ($item) {
                $customActions = [(object) ['name' => 'permissions', 'route' => route('roles/permissions', ['roleId' => $item->id]),
                    'css' => 'item-permissions', 'label' => __('role.Permissions'), 'method' => 'get']];
                return HtmlControls::GetActionColumn($this->routePath, $item, 'edit,delete', $customActions);
            })
            ->addColumn('select_row', function ($item) {
                return HtmlControls::GetSelectRowColumn($this->routePath . '_' . $item->id);
            })
            ->rawColumns(['actions', 'select_row'])
            ->make(true);
    }

    /**
     * role create page
     * @return {view} edit view
     */
    public function create()
    {
        $data = new $this->model();
        Form::updateModelFromRequest(request()->old(), $data, $this->updateFields);

        $page = (object) ['title' => __($this->translationPrefix . 'Role'), 'name' => __($this->translationPrefix . 'CreateNew'),
            'route' => route($this->routePath . '/create'), 'routeSave' => route($this->routePath . '/store'),
            'routePath' => $this->routePath, 'translationPrefix' => $this->translationPrefix, 'viewPath' => $this->viewPath];
        $breadcrumbPath = $this->routePath;
        return view($this->viewPath . '.edit',
            compact('data', 'page', 'breadcrumbPath'));
    }

    /**
     * store role to database -> create new entry
     * @param {object} $request http request
     * @return {view} edit view
     */
    public function store(Request $request)
    {
        $this->validateItemRequest($request);
        $id = $this->createItem($request);
        return redirect()->route($this->routePath . '/edit', ['id' => $id])->with(['success' => __('general.UpdatedSuccess')]);
    }

    /**
     * role edit page
     * @param {number} $id product id
     * @return {view} edit view
     */
    public function edit($id)
    {
        $data = $this->getItemForEdit($id);
        Form::updateModelFromRequest(request()->old(), $data, $this->updateFields);

        $page = (object) ['title' => __($this->translationPrefix . 'Roles'), 'name' => __('tables.Edit') . ': ' . $data->name,
            'route' => route($this->routePath . '/edit', ['id' => $id]),
            'routeSave' => route($this->routePath . '/update', ['id' => $id]),
            'routePath' => $this->routePath, 'translationPrefix' => $this->translationPrefix, 'viewPath' => $this->viewPath];
        $breadcrumbPath = $this->routePath;
        return view($this->viewPath . '.edit', compact('data', 'page', 'breadcrumbPath'));
    }

    /**
     * update role in database
     * @param {object} $request http request
     * @param {number} $id role id to update
     * @return {view} edit view
     */
    public function update(Request $request, $id)
    {
        $this->validateItemRequest($request, $id);

        $this->saveItem($request, $id);
        return redirect()->route($this->routePath . '/edit', ['id' => $id])->with(['success' => __('general.UpdatedSuccess')]);
    }

    /**
     * Delete role from db
     * @param {number} $id role id
     * @return {view} list view
     */
    public function delete($id)
    {
        $item = $this->model::findOrFail($id);
        if ($item) {
            $item->delete();
            return redirect()->route($this->routePath)->with(['success' => __($this->translationPrefix . 'DeleteSuccess')]);
        }
    }

    /**
     * export selected data
     * @param {object} $request http request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $data = $this->getExportData($request);
        $fileName = $this->routePath . '-' . date('Y-m-d');
        $exportFormat = $request->has('export_format') ? $request->export_format : null;

        switch ($exportFormat) {
            case 'csv':
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="' . $fileName . '.csv"',
                ];
                $csvContent = $this->getCsvContent($data);
                return response($csvContent)->withHeaders($headers);
                break;
            default:
                $pdf = PDF::loadView($this->viewPath . '.export-table', compact('data'));
                // download pdf file
                return $pdf->download($fileName . '.pdf');
                break;
        }
    }

    /**
     * get role item for edit
     * @param {number} $itemId role id
     * @return {object} role model
     */
    private function getItemForEdit($itemId)
    {
        $data = $this->model::select($this->selectFields)
            ->findOrFail($itemId);

        return $data;
    }

    /**
     * get data for export from db, for the given request
     * @param {object} $request http request
     * @return {array} of role models
     */
    private function getExportData(Request $request)
    {
        $data = $this->model::select($this->selectFields)->get();
        return $data;
    }

    /**
     * get the csv content for the given data
     * @param {array} $data array of role models
     * @return {string} csv content for provided data
     */
    private function getCsvContent($data)
    {
        $columnList = [__('tables.Id'), __('tables.Name'), __('tables.Slug')];

        return ExportUtils::getCsvContent($data, $columnList, $this->selectFields);
    }

    /** functions used to create / update role - BEGIN */

    /**
     * validate item request before create / save
     * uses laravel validation which, in case of error, will redirect to the edit/ create page, with the found errors
     * @param {object} $request http request
     * @param {number} $id role id
     */
    private function validateItemRequest(Request $request, $id = null)
    {
        $uniqueName = 'unique:roles,name';
        $uniqueSlug = 'unique:roles,slug';
        if ($id) {
            $uniqueName .= ',' . $id;
            $uniqueSlug .= ',' . $id;
        }

        $validationFields = [
            'name' => ['required', 'string', 'max:255', $uniqueName],
            'slug' => ['required', 'string', 'max:255', $uniqueSlug],
        ];
        $request->validate($validationFields);
    }

    /**
     * save new role in database, from provided request
     * @param {object} $request http request
     * @return {number} created id
     */
    private function createItem(Request $request)
    {
        $item = new $this->model();
        Form::updateModelFromRequest($request, $item, $this->updateFields);
        $item->save();
        return $item->id;
    }

    /**
     * update role in database, from provided request
     * @param {object} $request http request
     * @param {number} $itemId id of the role to save
     */
    private function saveItem(Request $request, $itemId)
    {
        $item = $this->model::findOrFail($itemId);
        Form::updateModelFromRequest($request, $item, $this->updateFields);
        $item->save();
    }

    /** functions used to create / update role - END */

}
