<?php

namespace App\Http\Controllers\BalanceSheets;

use App\Enums\BalanceType;
use App\Helpers\DataTableUtils;
use App\Helpers\ExportUtils;
use App\Helpers\Form;
use App\Helpers\HtmlControls;
use App\Helpers\SelectUtils;
use App\Helpers\UserUtils;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Target;
use Illuminate\Http\Request;
use PDF;
use Yajra\Datatables\Datatables;

/**
 * class which handles the list / create / update / delete of monthly balance data
 */
class MonthlyBalanceController extends Controller
{
    protected $viewPath = 'balancesheet.monthly-balance';
    protected $routePath = 'balancesheet/monthly-balance';
    protected $translationPrefix = 'balancesheet.balance.';
    protected $model = 'App\Models\MonthlyBalanceSheet';
    private $editFields; // fields that appear in the edit form
    private $updateFields; // fields that will be updated on save
    private $userId;
    private $userSettings;

    public function __construct()
    {
        $this->editFields = ['year', 'month', 'type_id', 'target_id', 'product_name', 'amount', 'unit_price'];
        $this->updateFields = ['year', 'month', 'type_id', 'target_id', 'amount', 'unit_price'];
        $this->middleware(function ($request, $next) {
            $this->userId = auth()->user()->id;
            return $next($request);
        });
    }

    /**
     * target list index
     * @return {view} list view
     */
    public function index()
    {
        $page = (object) ['title' => __($this->translationPrefix . 'MonthlyEntries'), 'route' => '', 'routeCreate' => route($this->routePath . '/create'),
            'routePath' => $this->routePath, 'translationPrefix' => $this->translationPrefix, 'viewPath' => $this->viewPath];
        $breadcrumbPath = $this->routePath;

        $balanceTypeSelectOptions = SelectUtils::getBalanceTypeSelectOptions(null);
        $targetSelectOptions = SelectUtils::getTargetSelectOptions(null);
        $yearSelectOptions = HtmlControls::GenerateDropDownListYear(10, 5, null);
        $monthSelectOptions = HtmlControls::GenerateDropDownListMonth(null);

        return view($this->viewPath . '.list', compact('page', 'breadcrumbPath', 'yearSelectOptions', 'monthSelectOptions',
            'balanceTypeSelectOptions', 'targetSelectOptions'));
    }

    /**
     * target list for datatable (ajax call)
     * @param {object} $request http request
     * @return {array} array with data for table
     */
    function list(Request $request) {
        $this->userSettings = UserUtils::getUserSetting($this->userId);
        $sortInfo = DataTableUtils::getRequestSort($request);
        $data = $this->model::with(['productInfo', 'typeInfo', 'targetInfo'])
            ->select(['id', 'year', 'month', 'type_id', 'product_id', 'target_id', 'amount', 'unit_price', 'price', 'updated_at'])
            ->where('user_id', $this->userId);

        $filterValue = $this->getColumnFilter($request, 'product_name');
        if ($filterValue) {
            $data->whereHas('productInfo', function ($query) use ($filterValue) {
                $query->where('name', 'like', '%' . $filterValue . '%');
            });
        }
        DataTableUtils::applyRequestSort($request, $data, ['type_name', 'product_name', 'target_name']);

        $sums = $this->getSumsForFilters($data, $request);

        return Datatables::of($data)
            ->filterColumn('price', function ($query, $keyword) {
                $this->applyQueryCustomFilter($query, $keyword, 'price');
            })
            ->filterColumn('year', function ($query, $keyword) {
                $this->applyQueryCustomFilter($query, $keyword, 'year');
            })
            ->filterColumn('month', function ($query, $keyword) {
                $this->applyQueryCustomFilter($query, $keyword, 'month');
            })
            ->addColumn('type_name', function ($item) {
                return $item->typeInfo ? $item->typeInfo->name : '';
            })
            ->addColumn('product_name', function ($item) {
                return $item->productInfo ? $item->productInfo->name : '';
            })
            ->addColumn('target_name', function ($item) {
                return $item->targetInfo ? $item->targetInfo->name : '';
            })
            ->addColumn('actions', function ($item) {
                return HtmlControls::GetActionColumn($this->routePath, $item, 'edit,delete');
            })
            ->addColumn('select_row', function ($item) {
                return HtmlControls::GetSelectRowColumn('target_' . $item->id);
            })
            ->with($sums)
            ->rawColumns(['actions', 'select_row'])
            ->make(true);
    }

    /**
     * target create
     * @return {view} edit view
     */
    public function create()
    {
        $data = new $this->model();
        if (empty(request()->old())) {
            $data->year = date('Y');
            $data->month = date('m');
            $data->type_id = 1;
        }
        Form::updateModelFromRequest(request()->old(), $data, $this->editFields);
        $data->month = (int) $data->month;
        $data->year = (int) $data->year;

        $balanceTypeSelectOptions = SelectUtils::getBalanceTypeSelectOptions($data->type_id);
        $targetSelectOptions = SelectUtils::getTargetSelectOptions($data->target_id);
        $yearSelectOptions = HtmlControls::GenerateDropDownListYear(2, 5, $data->year);
        $monthSelectOptions = HtmlControls::GenerateDropDownListMonth($data->month);
        $page = (object) ['title' => __($this->translationPrefix . 'MonthlyEntries'), 'name' => __($this->translationPrefix . 'CreateNew'),
            'route' => route($this->routePath . '/create'), 'routeSave' => route($this->routePath . '/store'),
            'routePath' => $this->routePath, 'translationPrefix' => $this->translationPrefix];
        $breadcrumbPath = $this->routePath;
        return view($this->viewPath . '.edit',
            compact('data', 'page', 'breadcrumbPath', 'yearSelectOptions', 'monthSelectOptions',
                'balanceTypeSelectOptions', 'targetSelectOptions'));
    }

    /**
     * store target to database -> create new entry
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
     * target edit
     * @param {number} $id product id
     * @return {view} edit view
     */
    public function edit($id)
    {
        $data = $this->getItemForEdit($id);
        Form::updateModelFromRequest(request()->old(), $data, $this->editFields);

        $data->month = (int) $data->month;
        $data->year = (int) $data->year;

        $balanceTypeSelectOptions = SelectUtils::getBalanceTypeSelectOptions($data->type_id);
        $targetSelectOptions = SelectUtils::getTargetSelectOptions($data->target_id);
        $yearSelectOptions = HtmlControls::GenerateDropDownListYear(2, 5, $data->year);
        $monthSelectOptions = HtmlControls::GenerateDropDownListMonth($data->month);
        $page = (object) ['title' => __($this->translationPrefix . 'MonthlyEntries'), 'name' => __('tables.Edit') . ': ' . $data->name,
            'route' => route($this->routePath . '/edit', ['id' => $id]),
            'routeSave' => route($this->routePath . '/update', ['id' => $id]), 'routePath' => $this->routePath, 'translationPrefix' => $this->translationPrefix];
        $breadcrumbPath = $this->routePath;
        return view($this->viewPath . '.edit', compact('data', 'page', 'breadcrumbPath', 'yearSelectOptions', 'monthSelectOptions',
            'balanceTypeSelectOptions', 'targetSelectOptions'));
    }

    /**
     * update target in database
     * @param {object} $request http request
     * @param {number} $id target id to update
     * @return {view} edit view
     */
    public function update(Request $request, $id)
    {
        $this->validateItemRequest($request, $id);

        $this->saveItem($request, $id);
        return redirect()->route($this->routePath . '/edit', ['id' => $id])->with(['success' => __('general.UpdatedSuccess')]);
    }

    /**
     * delete target from db
     * @param {number} $id target id
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
     * get target item for edit
     * @param {number} $itemId target id
     * @return {object} target model
     */
    private function getItemForEdit($itemId)
    {
        $data = $this->model::with(['productInfo'])
            ->select(['id', 'year', 'month', 'type_id', 'product_id', 'target_id', 'amount', 'unit_price', 'updated_at'])
            ->where('user_id', $this->userId)
            ->findOrFail($itemId);

        $data->product_name = $data->productInfo->name;

        return $data;
    }

    /**
     * get data for export from db, for the given request
     * @param {object} $request http request
     * @return {array} of target models
     */
    private function getExportData(Request $request)
    {
        // selecting PDF view
        $query = $this->model::with(['productInfo', 'targetInfo', 'typeInfo'])
            ->select(['id', 'year', 'month', 'type_id', 'product_id', 'target_id', 'amount', 'unit_price', 'price'])
            ->where('user_id', $this->userId);

        $this->applyExportFilters($query, $request);
        $data = $query->get();

        if ($data) {
            foreach ($data as &$row) {
                $row->type_name = $row->typeInfo->name;
                $row->product_name = $row->productInfo->name;
                $row->target_name = $row->targetInfo->name;
                if ($row->type === 2) {
                    $row->price = -$row->price;
                }
            }
        }
        return $data;
    }

    /**
     * get the csv content for the given data
     * @param {array} $data array of target models
     * @return {string} csv content for provided data
     */
    private function getCsvContent($data)
    {
        $fieldList = ['id', 'year', 'month', 'type_name', 'product_name', 'target_name', 'amount', 'unit_price', 'price'];
        $columnList = [__('tables.Id'), __('tables.Year'), __('tables.Month'), __('tables.Type'), __('tables.Product'), __('tables.Target')
            , __('tables.Amount'), __('tables.UnitPrice'), __('tables.Price')];

        return ExportUtils::getCsvContent($data, $columnList, $fieldList);
    }

    /** functions used to create / update target - BEGIN */

    /**
     * validate item request before create / save
     * uses laravel validation which, in case of error, will redirect to the edit/ create target, with the found errors
     * @param {object} $request http request
     * @param {number} $id target id
     */
    private function validateItemRequest(Request $request, $id = null)
    {
        $validationFields = [
            'year' => ['required', 'numeric', 'min:2022'],
            'month' => ['required', 'numeric', 'min:1', 'max:12'],
            'type_id' => ['required', 'numeric'],
            'target_id' => ['required', 'numeric'],
            'product_name' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'unit_price' => ['required', 'numeric', 'min:0.01'],
        ];
        $request->validate($validationFields);
    }

    /**
     * save new target in database, from provided request
     * @param {object} $request http request
     * @return {number} created id
     */
    private function createItem(Request $request)
    {
        $item = new $this->model();
        Form::updateModelFromRequest($request, $item, $this->updateFields);
        $item->price = $item->unit_price * $item->amount;
        $item->product_id = $this->getProductId($request->product_name);
        $item->user_id = $this->userId;
        $item->save();
        return $item->id;
    }

    /**
     * update target in database, from provided request
     * @param {object} $request http request
     * @param {number} $itemId id of the target to save
     */
    private function saveItem(Request $request, $itemId)
    {
        $item = $this->model::findOrFail($itemId);
        Form::updateModelFromRequest($request, $item, $this->updateFields);
        $item->price = $item->unit_price * $item->amount;
        $item->product_id = $this->getProductId($request->product_name);
        $item->save();
    }

    /**
     * get product id for the given text; if not found, create a new product and return the id
     * @param {name} $product name
     * @return {number} existing id of the created id
     */
    private function getProductId($name)
    {
        $item = Product::where('user_id', $this->userId)
            ->where('name', $name)
            ->select('id')
            ->first();

        if ($item) {
            return $item->id;
        }

        $item = new Product();
        $item->name = $name;
        $item->user_id = $this->userId;
        $item->save();
        return $item->id;
    }

    /** functions used to create / update target - END */

    /** functions used for custom filtering - BEGIN */

    /**
     * get column filter from request, for the specified field name
     * the request comes from datatable
     * @param {object} $request http request from datatables
     * @param {string} $fieldName name of the field for which to get the filter
     * @return {string} value of the filter
     */
    private function getColumnFilter($request, $fieldName)
    {
        // if ($request->has('search') && $request->search['value'] !== null) {
        //     return $request->search['value'];
        // }
        $filters = $request->has('columns') ? $request->columns : [];
        foreach ($filters as $filter) {
            if ($filter['name'] === $fieldName) {
                return $filter['search']['value'];
            }
        }
        return null;
    }

    /**
     * apply the filters form the query to the specified request
     * the request comes from datatable
     * @param {object} $query eloquent query
     * @param {object} $request http request from datatables
     */
    private function applyTableFilters($query, $request)
    {
        $fields = ['price', 'year', 'month', 'type_id', 'target_id'];
        foreach ($fields as $field) {
            $filterValue = $this->getColumnFilter($request, $field);
            if ($filterValue) {
                $this->applyQueryCustomFilter($query, $filterValue, $field);
            }
        }
    }

    /**
     * calculate the credit, debit and net sums for the specified query and request
     * the request comes from datatable
     * @param {object} $query eloquent query
     * @param {object} $request http request from datatables
     * @return {array} ['sumCredit', 'sumDebit', 'sumNet']
     */
    private function getSumsForFilters($query, $request)
    {
        $dataQuerySumCredit = clone $query;
        $dataQuerySumDebit = clone $query;
        $this->applyTableFilters($dataQuerySumCredit, $request);
        $this->applyTableFilters($dataQuerySumDebit, $request);
        $sumCredit = $dataQuerySumCredit->where('type_id', BalanceType::Credit)->sum('price');
        $sumDebit = $dataQuerySumDebit->where('type_id', BalanceType::Debit)->sum('price');
        $sumNet = $sumCredit - $sumDebit;
        return ['sumCredit' => $sumCredit, 'sumDebit' => $sumDebit, 'sumNet' => $sumNet];
    }

    /**
     * apply custom filter to the given query
     * @param {object} $query eloquent query
     * @param {string} $keyword filter value
     * @param {string} $field column for which to apply the query
     */
    private function applyQueryCustomFilter($query, $keyword, $field)
    {
        $filterValue = json_decode($keyword);
        if (gettype($filterValue) !== 'object') {
            $query->where($field, $filterValue);
        } else {
            if ($filterValue->min) {
                $query->where($field, '>=', $filterValue->min);
            }
            if ($filterValue->max) {
                $query->where($field, '<=', $filterValue->max);
            }
        }
    }
    /** functions used for custom filtering - END */

    /**
     * apply the filters form the query to the specified request
     * the request comes from datatable
     * @param {object} $query eloquent query
     * @param {object} $request http request from datatables
     */
    private function applyExportFilters($query, $request)
    {
        if (!$request->has('exportFilters')) {
            return;
        }
        $fields = ['price', 'year', 'month', 'type_id', 'target_id'];
        $filters = json_decode($request->exportFilters);

        $filterValue = isset($filters->product) ? $filters->product : null;
        if ($filterValue) {
            $query->whereHas('productInfo', function ($query) use ($filterValue) {
                $query->where('name', 'like', '%' . $filterValue . '%');
            });
        }

        foreach ($fields as $field) {
            $filterValue = isset($filters->{$field}) ? $filters->{$field} : null;
            if ($filterValue) {
                $this->applyQueryCustomFilter($query, $filterValue, $field);
            }
        }
    }

}
