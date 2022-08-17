<?php

namespace App\Http\Controllers\BalanceSheets;

use App\Enums\BalanceType;
use App\Helpers\DateUtils;
use App\Helpers\Form;
use App\Helpers\SelectUtils;
use App\Helpers\UserUtils;
use App\Http\Controllers\Controller;
use App\Models\DailyBalanceSheet;
use App\Models\MonthlyBalanceSheet;
use App\Models\Target;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

/**
 * class which handles the list / create / update / delete of daily balance data
 */
class StatisticsController extends Controller
{
    protected $viewPath = 'balancesheet.statistics';
    protected $routePath = 'balancesheet/statistics';
    protected $translationPrefix = 'balancesheet.balance.';
    private $userSettings;

    public function __construct()
    {
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
        $this->userSettings = UserUtils::getUserSetting($this->userId);
        $userDateFormat = $this->userSettings->date_format;
        $page = (object) ['title' => __($this->translationPrefix . 'Statistics'),
            'route' => '', 'routePath' => $this->routePath, 'isList' => true,
            'translationPrefix' => $this->translationPrefix, 'viewPath' => $this->viewPath];
        $breadcrumbPath = $this->routePath;

        $balanceTypeSelectOptions = SelectUtils::getBalanceTypeSelectOptions(null);
        $targetSelectOptions = SelectUtils::getTargetSelectOptions(null);

        $data = $this->list(request());

        return view($this->viewPath . '.list', compact('page', 'breadcrumbPath', 'balanceTypeSelectOptions', 'targetSelectOptions', 'userDateFormat', 'data'));
    }

    /**
     * target list for datatable (ajax call)
     * @param {object} $request http request
     * @return {array} array with data for table
     */
    function list(Request $request) {
        $this->userSettings = UserUtils::getUserSetting($this->userId);
        $queryDaily = DailyBalanceSheet::with(['productInfo', 'typeInfo', 'targetInfo'])
            ->select(['id', 'price'])
            ->where('user_id', $this->userId);

        $queryMonthly = MonthlyBalanceSheet::with(['productInfo', 'typeInfo', 'targetInfo'])
            ->select(['id', 'date_added', 'type_id', 'product_id', 'target_id', 'amount', 'unit_price', 'price'])
            ->where('user_id', $this->userId);

        if (!$request->has('filters')) {
            $dateStart = \Carbon\Carbon::now()->startOfYear();
            $dateEnd = \Carbon\Carbon::now();

            $dateStartMysql = $dateStart->format(config('settings.mysql_date_format'));
            $dateStartDisplay = $dateStart->format($this->userSettings->date_format_php);

            $dateEndMysql = $dateEnd->format(config('settings.mysql_date_format'));
            $dateEndDisplay = $dateEnd->format($this->userSettings->date_format_php);

            $queryDaily->whereBetween('date_added', [$dateStartMysql, $dateEndMysql]);

            $queryMonthly->where('year', $dateStart->format('Y'));
            $queryMonthly->whereBetween('month', [$dateStart->format('m'), $dateEnd->format('m')]);

            $filtersInfo = ['date_added' => (object) ['min' => $dateStartDisplay, 'max' => $dateEndDisplay]];
        } else {
            $filtersInfo = $this->applyFilters($queryDaily, $request, 'daily');
            $this->applyFilters($queryMonthly, $request, 'monthly');
        }

        $daily = $this->getSumsForFilters($queryDaily);
        $monthly = $this->getSumsForFilters($queryMonthly);

        return (object) ['daily' => $daily, 'monthly' => $monthly, 'filters' => $filtersInfo, 'filtersSummary' => $this->getFiltersSummary($filtersInfo)];
    }

    /** functions used for custom filtering - BEGIN */

    private function getFiltersSummary($filters)
    {
        $summary = '';
        foreach ($filters as $key => $filter) {
            $fieldName = $key === 'date_added' ? 'Date' : ucfirst($key);
            if (gettype($filter) === 'object') {
                $summary .= __('tables.' . $fieldName) . ': ';
                if ($filter->min && $filter->max) {
                    $summary .= __($this->translationPrefix . 'filter_between') . ' ' . $filter->min . ' - ' . $filter->max;
                } else if ($filter->min) {
                    $summary .= __($this->translationPrefix . 'filter_min') . ' ' . $filter->min;
                } else if ($filter->max) {
                    $summary .= __($this->translationPrefix . 'filter_max') . ' ' . $filter->max;
                }
            } else {
                $summary .= ': ' . $filter;
            }
            $summary .= '|';
        }
        return $summary;
    }

    /**
     * get column filter from request, for the specified field name
     * the request comes from datatable
     * @param {object} $request http request from datatables
     * @param {string} $fieldName name of the field for which to get the filter
     * @return {string} value of the filter
     */
    private function getFilter($request, $fieldName)
    {
        return $request->has($fieldName) ? $request->{$fieldName} : null;
    }

    /**
     * calculate the credit, debit and net sums for the specified query and request
     * the request comes from datatable
     * @param {object} $query eloquent query
     * @return {array} ['sumCredit', 'sumDebit', 'sumNet']
     */
    private function getSumsForFilters($query)
    {
        $dataQuerySumCredit = clone $query;
        $dataQuerySumDebit = clone $query;
        $sumCredit = $dataQuerySumCredit->where('type_id', BalanceType::Credit)->sum('price');
        $sumDebit = $dataQuerySumDebit->where('type_id', BalanceType::Debit)->sum('price');
        $sumNet = $sumCredit - $sumDebit;

        return (object) ['sumCredit' => $sumCredit, 'sumDebit' => $sumDebit, 'sumNet' => $sumNet];
    }

    /**
     * apply custom filter to the given query
     * @param {object} $query eloquent query
     * @param {string} $keyword filter value
     * @param {string} $field column for which to apply the query
     * @param {string} $queryType  'monthly' or 'daily'
     * @return {any} the filter value
     */
    private function applyQueryCustomFilter($query, $keyword, $field, $queryType)
    {
        $filterInfo = null;
        if (!$keyword) {
            return $filterInfo;
        }
        // $filterValue = json_decode($keyword);
        $filterValue = $keyword;
        if (!$filterValue) {
            return $filterInfo;
        }
        if (gettype($filterValue) !== 'object') {
            if ($filterValue) {
                $query->where($field, $filterValue);
                $filterInfo = $filterValue;
            }
        } else {
            $yearStart = null;
            $monthStart = null;
            $filterInfo = (object) ['min' => null, 'max' => null];

            if ($filterValue->min) {
                $filterInfo->min = $filterValue->min;
                if ($field === 'date_added') {
                    $filterValue->min = DateUtils::userToMysqlDate($filterValue->min, $this->userSettings->date_format_php, ['startDay' => true]);
                }
                if ($queryType === 'monthly' && $field === 'date_added') {
                    $timeStamp = strtotime($filterValue->min);
                    $yearStart = date('Y', $timeStamp);
                    $monthStart = date('m', $timeStamp);
                    $query->where('year', '>=', $yearStart);

                    $query->where(function ($subQuery) use ($yearStart, $monthStart) {
                        $subQuery->where(function ($subQuery1) use ($yearStart, $monthStart) {
                            $subQuery1->where('year', $yearStart);
                            $subQuery1->where('month', '>=', $monthStart);
                        });
                        $subQuery->orWhere(function ($subQuery2) use ($yearStart) {
                            $subQuery2->where('year', '>', $yearStart);
                        });
                    });
                } else {
                    $query->where($field, '>=', $filterValue->min);
                }
            }
            if ($filterValue->max) {
                $filterInfo->max = $filterValue->max;
                if ($field === 'date_added') {
                    $filterValue->max = DateUtils::userToMysqlDate($filterValue->max, $this->userSettings->date_format_php, ['endDay' => true]);
                }
                if ($queryType === 'monthly' && $field === 'date_added') {
                    $timeStamp = strtotime($filterValue->max);
                    $yearEnd = date('Y', $timeStamp);
                    $monthEnd = date('m', $timeStamp);
                    $query->where('year', '<=', $yearEnd);

                    $query->where(function ($subQuery) use ($yearEnd, $monthEnd) {
                        $subQuery->where(function ($subQuery1) use ($yearEnd, $monthEnd) {
                            $subQuery1->where('year', $yearEnd);
                            $subQuery1->where('month', '<=', $monthEnd);
                        });
                        $subQuery->orWhere(function ($subQuery2) use ($yearEnd) {
                            $subQuery2->where('year', '<', $yearEnd);
                        });
                    });
                } else {
                    $query->where($field, '<=', $filterValue->max);
                }
            }
        }

        return $filterInfo;
    }
    /** functions used for custom filtering - END */

    /**
     * apply the filters form the query to the specified request
     * the request comes from datatable
     * @param {object} $query eloquent query
     * @param {object} $request http request from datatables
     * @param {string} $queryType  'monthly' or 'daily'
     */
    private function applyFilters($query, $request, $queryType)
    {
        $filtersInfo = [];
        if (!$request->has('filters')) {
            return $filtersInfo;
        }

        $fields = ['price', 'date_added', 'type_id', 'target_id'];
        $filters = json_decode($request->filters);
        $filterValue = isset($filters->product) ? $filters->product : null;
        if ($filterValue) {
            $query->whereHas('productInfo', function ($query) use ($filterValue) {
                $query->where('name', 'like', '%' . $filterValue . '%');
            });
            $filtersInfo['product'] = $filterValue;
        }

        foreach ($fields as $field) {

            $filterValue = isset($filters->{$field}) ? $filters->{$field} : null;
            if ($filterValue) {

                $filterInfo = $this->applyQueryCustomFilter($query, $filterValue, $field, $queryType);
                if ($filterInfo) {
                    $filtersInfo[$field] = $filterInfo;
                }
            }
        }
        return $filtersInfo;
    }
}
