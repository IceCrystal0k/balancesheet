<?php

namespace App\Http\Controllers\Users;

use App\Enums\UserStatus;
use App\Helpers\ExportUtils;
use App\Helpers\FileUtils;
use App\Helpers\Form;
use App\Helpers\HtmlControls;
use App\Helpers\SelectUtils;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Timezone;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PDF;
use Yajra\Datatables\Datatables;

/**
 * class which handles the list / create / update / delete of users
 */
class UserController extends Controller
{
    protected $viewPath = 'users';
    protected $routePath = 'users';
    protected $translationPrefix = 'user.';
    protected $model = 'App\Models\User';

    private $editFields;

    public function __construct()
    {
        $this->editFields = ['first_name', 'last_name', 'email', 'password', 'currency', 'status',
            'date_format', 'date_format_separator'];
    }

    /**
     * user list index
     * @return {view} list view
     */
    public function index()
    {
        $page = (object) ['title' => __($this->translationPrefix . 'Users'), 'route' => '', 'routeCreate' => route($this->routePath . '/create'),
            'routePath' => $this->routePath, 'translationPrefix' => $this->translationPrefix, 'viewPath' => $this->viewPath];
        $breadcrumbPath = 'users';
        $statusFilter = HtmlControls::ArrayToCheckedList(config('settings.user_status'), 'general.Status.');
        $googleSelectOptions = HtmlControls::GenerateDropDownListBoolean('');
        $facebookSelectOptions = $googleSelectOptions;
        return view($this->viewPath . '.list', compact('page', 'breadcrumbPath',
            'googleSelectOptions', 'facebookSelectOptions', 'statusFilter'));
    }

    /**
     * user list for datatable (ajax call)
     * @param {object} $request http request
     * @return {array} array with data for table
     */
    function list(Request $request) {
        $data = $this->model::select(['id', \DB::raw("CONCAT(first_name,' ', last_name) as full_name"), 'email', 'updated_at', 'google_id', 'fb_id', 'status']);
        return Datatables::of($data)
            ->filterColumn('google_id', function ($query, $keyword) {
                switch ($keyword) {
                    case '0':$query->whereNull('google_id');break;
                    case '1':$query->whereNotNull('google_id');break;
                }
            })
            ->filterColumn('fb_id', function ($query, $keyword) {
                switch ($keyword) {
                    case '0':$query->whereNull('fb_id');break;
                    case '1':$query->whereNotNull('fb_id');break;
                }
            })
            ->filterColumn('full_name', function ($query, $keyword) {
                $sql = "CONCAT(first_name,' ', last_name) LIKE ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('status_name', function ($query, $keyword) {
                $arrayKey = array_search($keyword, config('settings.user_status'));
                if ($arrayKey !== false) {
                    $query->where('status', $arrayKey);
                }
            })
            ->filterColumn('status', function ($query, $keyword) {
                $statusList = explode(',', $keyword);
                $statusListFiltered = [];
                foreach ($statusList as $val) {
                    if (is_numeric($val)) {
                        array_push($statusListFiltered, (int) $val);
                    }
                }
                if ($statusListFiltered && count($statusListFiltered) > 0) {
                    $query->whereIn('status', $statusListFiltered);
                }
            })
            ->addColumn('status_name', function ($item) {
                return __($this->translationPrefix . 'Status.' . config('settings.user_status')[$item->status]);
            })
            ->addColumn('google', function ($item) {
                return HtmlControls::GetBooleanControl($item->google_id);
            })
            ->addColumn('facebook', function ($item) {
                return HtmlControls::GetBooleanControl($item->fb_id);
            })
            ->addColumn('updated_at', function ($item) {
                return date(config('settings.date_format_php')[1], strtotime($item->updated_at));
            })
            ->addColumn('actions', function ($item) {
                $actionColumn = null;
                // for own user show only edit menu
                if ($item->id === auth()->user()->id) {
                    $actionColumn = HtmlControls::GetActionColumn('users', $item, 'edit');
                } else {
                    switch ($item->status) {
                        case UserStatus::Pending: // pending
                            $actionColumn = HtmlControls::GetActionColumn('users', $item, 'edit,activate,delete,remove');
                            break;
                        case UserStatus::Active: // active
                            $actionColumn = HtmlControls::GetActionColumn('users', $item, 'edit,deactivate,delete,remove');
                            break;
                        case UserStatus::Deleted:
                            $actionColumn = HtmlControls::GetActionColumn('users', $item, 'edit,activate,remove');
                            break;
                    }
                }

                return $actionColumn;
            })
            ->addColumn('select_row', function ($item) {
                return HtmlControls::GetSelectRowColumn('user_' . $item->id);
            })
            ->rawColumns(['actions', 'select_row', 'facebook', 'google'])
            ->make(true);
    }

    /**
     * user create page
     * @return {view} edit view
     */
    public function create()
    {
        $data = new $this->model();
        if (empty(request()->old())) {
            $data->status = 1; // default active
        }

        Form::updateModelFromRequest(request()->old(), $data, $this->editFields);

        $dateFormatSelectOptions = SelectUtils::getDateFormatSelectOptions($data->date_format);
        $dateFormatSeparatorSelectOptions = SelectUtils::getDateFormatSeparatorSelectOptions($data->date_format_separator);
        $currencySelectOptions = SelectUtils::getCurrencySelectOptions($data->currency);

        $page = (object) ['title' => __($this->translationPrefix . 'Users'), 'name' => __($this->translationPrefix . 'CreateNew'),
            'route' => route($this->routePath . '/create'), 'routeSave' => route($this->routePath . '/store'),
            'routePath' => $this->routePath, 'translationPrefix' => $this->translationPrefix, 'viewPath' => $this->viewPath,
        ];
        $breadcrumbPath = 'users';
        return view($this->viewPath . '.edit',
            compact('data', 'page', 'dateFormatSeparatorSelectOptions', 'dateFormatSelectOptions',
                'currencySelectOptions', 'breadcrumbPath'));
    }

    /**
     * store user to database -> create new entry
     * @param {object} $request http request
     * @return {view} edit view
     */
    public function store(Request $request)
    {
        $this->validateItemCreateRequest($request);
        $id = $this->createItem($request);
        $this->saveUserInfo($request, $id);
        return redirect()->route($this->routePath . '/edit', ['id' => $id])->with(['success' => __('general.UpdatedSuccess')]);
    }

    /**
     * user edit page
     * @return {view} edit view
     */
    public function edit($id)
    {
        $data = $this->getItemForEdit($id);
        Form::updateModelFromRequest(request()->old(), $data, $this->editFields);

        $dateFormatSelectOptions = SelectUtils::getDateFormatSelectOptions($data->date_format);
        $dateFormatSeparatorSelectOptions = SelectUtils::getDateFormatSeparatorSelectOptions($data->date_format_separator);
        $currencySelectOptions = SelectUtils::getCurrencySelectOptions($data->currency);

        $page = (object) ['title' => __($this->translationPrefix . 'Users'), 'name' => __('tables.Edit') . ': ' . $data->name,
            'route' => route($this->routePath . '/edit', ['id' => $id]),
            'routeSave' => route($this->routePath . '/update', ['id' => $id]),
            'routePath' => $this->routePath, 'translationPrefix' => $this->translationPrefix, 'viewPath' => $this->viewPath,
        ];
        $breadcrumbPath = 'users';
        return view($this->viewPath . '.edit', compact('data', 'page', 'dateFormatSelectOptions', 'dateFormatSeparatorSelectOptions',
            'currencySelectOptions', 'breadcrumbPath'));
    }

    /**
     * update user in database
     * @param {object} $request http request
     * @param {number} $id user id to update
     * @return {view} edit view
     */
    public function update(Request $request, $id)
    {
        $this->validateItemUpdateRequest($request);

        $this->saveItem($request, $id);
        $userInfo = $this->saveUserInfo($request, $id);
        if ($id === auth()->user()->id) {
            UserUtils::updateUserSetting(auth()->user()->id, $userInfo);
        }
        return redirect()->route($this->routePath . '/edit', ['id' => $id])->with(['success' => __('general.UpdatedSuccess')]);
    }

    /**
     * update user password in database
     * @param {object} $request http request
     * @param {number} $id user id
     */
    public function updatePassword(Request $request, $id)
    {
        $this->validatePasswordUpdateRequest($request);

        $this->savePassword($request, $id);
        return redirect()->route($this->routePath . '/edit', ['id' => $id])->with(['success' => __('account.PasswordUpdatedSuccess')]);
    }

    /**
     * update user email in database
     * @param {object} $request http request
     * @param {number} $id user id
     */
    public function updateEmail(Request $request, $id)
    {
        $this->validateEmailUpdateRequest($request);

        $this->saveEmail($request, $id);
        return redirect()->route($this->routePath . '/edit', ['id' => $id])->with(['success' => __('account.EmailUpdatedSuccess')]);
    }

    /**
     * set user status to deleted
     * @param {number} $id user id
     */
    public function delete($id)
    {
        return $this->setUserStatus($id, UserStatus::Deleted);
    }

    /**
     * Activate user
     * @param {number} $id user id
     */
    public function activate($id)
    {
        return $this->setUserStatus($id, UserStatus::Active);
    }

    /**
     * Deactivate user
     * @param {number} $id user id
     */
    public function deactivate($id)
    {
        return $this->setUserStatus($id, UserStatus::Pending);
    }

    /**
     * completely remove user and references from db
     * @param {number} $id user id
     */
    public function remove($id)
    {
        $item = $this->model::findOrFail($id);
        if ($item) {
            $userInfo = UserInfo::where('user_id', $item->id)->first();
            if ($userInfo) {
                $userInfo->delete();
            }
            $item->delete();
            return redirect()->route($this->routePath)->with(['success' => __($this->translationPrefix . 'RemoveSuccess')]);
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
        $fileName = 'users-' . date('Y-m-d');
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
     * get user item for edit
     * @param {number} $itemId user id
     * @return {object} user model
     */
    private function getItemForEdit($itemId)
    {
        $data = $this->model::leftJoin('user_infos', 'users.id', '=', 'user_infos.user_id')
            ->select(['users.id', 'user_id', 'first_name', 'last_name', 'email', 'google_id', 'fb_id', 'status',
                'avatar', 'date_format', 'date_format_separator',
                'currency', 'user_infos.updated_at'])
            ->findOrFail($itemId);

        $this->formatUserData($data);
        return $data;
    }

    /**
     * format user data for display
     * @param {object} $data user model
     */
    private function formatUserData($data)
    {
        $data->name = $data->first_name . ' ' . $data->last_name;
        $data->communication = $data->communication ? json_decode($data->communication) : null;
        $data->communication_display = $data->communication ? implode(', ', array_keys((array) $data->communication)) : '';
        if ($data->timezone) {
            $data->timezone = (int) $data->timezone;
        }

        $data->avatar_card = FileUtils::getUserAvatarUrl($data, '160x160', 'user/picture');
        $data->avatar_edit = FileUtils::getUserAvatarUrl($data, '125x125', 'user/picture');
        $data->hasAvatar = strpos($data->avatar_card, 'blank.png') === false;
    }

    /**
     * get data for export from db, for the given request
     * @param {object} $request http request
     * @return {array} of user models
     */
    private function getExportData(Request $request)
    {
        $exportDateRange = $request->has('export_daterange') ? $request->export_daterange : null;
        $exportStatus = $request->has('export_status') ? $request->export_status : null;
        // selecting PDF view
        $query = $this->model::select(['id', \DB::raw("CONCAT(first_name,' ', last_name) as full_name"), 'email',
            'updated_at', 'google_id', 'fb_id', 'status']);

        if ($exportDateRange) {
            list($dateStart, $dateEnd) = explode(' - ', $exportDateRange);
            try {
                $dateStart = \Carbon\Carbon::parse($dateStart);
                $dateEnd = \Carbon\Carbon::parse($dateEnd);
                $query->whereBetween('created_at', [$dateStart, $dateEnd]);
            } catch (\Exception$e) {
                // show some error
            }
        }
        if ($exportStatus) {
            $statusList = is_array($exportStatus) ? $exportStatus : [];
            $statusListFiltered = [];
            foreach ($statusList as $val) {
                if (is_numeric($val)) {
                    array_push($statusListFiltered, (int) $val);
                }
            }
            if ($statusListFiltered && count($statusListFiltered) > 0) {
                $query->whereIn('status', $statusListFiltered);
            }
        }

        $data = $query->get();
        foreach ($data as &$row) {
            // updated_at format can't be changed, so add a new date attribute
            $row->updated_date = date(config('settings.date_format_php')[1], strtotime($row->updated_at));
            $row->google = $row->google_id ? __('Yes') : '';
            $row->facebook = $row->fb_id ? __('Yes') : '';
        }

        return $data;
    }

    /**
     * get the csv content for the given data
     * @param {array} $data array of user models
     * @return {string} csv content for provided data
     */
    private function getCsvContent($data)
    {
        $fieldList = ['id', 'full_name', 'email', 'updated_date', 'google', 'facebook', 'status_name'];
        $columnList = [__('tables.Id'), __('tables.Name'), __('tables.Email'), __('tables.UpdatedAt'),
            __('tables.Google'), __('tables.Facebook'), __('tables.Status')];

        return ExportUtils::getCsvContent($data, $columnList, $fieldList);
    }

    /** functions used to create / update user - BEGIN */

    /**
     * validate item request before create
     * uses laravel validation which, in case of error, will redirect to the edit/ create page, with the found errors
     * @param {object} $request http request
     */
    private function validateItemCreateRequest(Request $request)
    {
        $validationFields = [
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'last_name' => ['required', 'string', 'max:255'],
        ];
        $request->validate($validationFields);
    }

    /**
     * validate item request before save
     * uses laravel validation which, in case of error, will redirect to the edit/ create page, with the found errors
     * @param {object} $request http request
     */
    private function validateItemUpdateRequest(Request $request)
    {
        $validationFields = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
        ];
        $request->validate($validationFields);
    }

    /**
     * save new user in database, from provided request
     * @param {object} $request http request
     * @return {number} created id
     */
    private function createItem(Request $request)
    {
        $item = new User();
        $updateFields = ['first_name', 'last_name', 'email', 'password', ['field' => 'status', 'type' => 'bool']];
        Form::updateModelFromRequest($request, $item, $updateFields);
        $item->password = \Hash::make($item->password);
        $item->email_verified_at = \Carbon\Carbon::now();
        $item->save();
        return $item->id;
    }

    /**
     * update product in database, from provided request
     * @param {object} $request http request
     * @param {number} $itemId id of the product to save
     */
    private function saveItem(Request $request, $itemId)
    {
        $item = $this->model::findOrFail($itemId);
        $updateFields = ['first_name', 'last_name', ['field' => 'status', 'type' => 'bool']];
        Form::updateModelFromRequest($request, $item, $updateFields);
        $item->save();
    }

    /**
     * save user additional information
     * @param {object} $request http request
     * @param {number} $itemId id of the product to save
     */
    private function saveUserInfo(Request $request, $itemId)
    {
        $userInfo = UserInfo::where('user_id', $itemId)->first();
        if (!$userInfo) {
            $userInfo = new UserInfo();
            $userInfo->user_id = $itemId;
        }
        $updateFields = ['date_format', 'date_format_separator', 'currency'];
        Form::updateModelFromRequest($request, $userInfo, $updateFields);

        $storeOptions = ['newFileName' => 'avatar'];
        if ($request->avatar_remove === '1') {
            $storeOptions['removeFileName'] = $userInfo->avatar;
            // if avatar is removed, delete all cache file
            File::delete(File::glob(storage_path('app/public/account/user_' . $itemId . '/avatar-*')));
        }
        $avatar = FileUtils::storeRequestFile($request, 'avatar', 'public/account/user_' . $itemId . '/', $storeOptions);
        if ($avatar) {
            $userInfo->avatar = $avatar;
            // if avatar is changed, delete all cache files
            File::delete(File::glob(storage_path('app/public/account/user_' . $itemId . '/avatar-*')));
        }

        $userInfo->save();

        return $userInfo;
    }

    /** functions used to create / update user - END */

    /** Update user password - BEGIN */

    /**
     * validate password update request
     * uses laravel validation which, in case of error, will redirect to the edit/ create page, with the found errors
     * @param {object} $request http request
     */
    private function validatePasswordUpdateRequest(Request $request)
    {
        $validationFields = [
            'new_password' => ['required', 'string', 'max:255', 'min:8'],
            'password_confirmation' => ['required', 'string', 'same:new_password'],
        ];
        $request->validate($validationFields);
    }

    /**
     * save password in database for specified $userId
     * @param {object} $request http request
     * @param {number} $userId
     */
    private function savePassword(Request $request, $userId)
    {
        $item = $this->model::findOrFail($userId);
        $item->password = \Hash::make($request->new_password);
        $item->save();
    }

    /**
     * validate email update request
     * uses laravel validation which, in case of error, will redirect to the edit/ create page, with the found errors
     * @param {object} $request http request
     */
    private function validateEmailUpdateRequest(Request $request)
    {
        $validationFields = [
            'new_email' => ['required', 'string', 'max:255', 'min:5', 'unique:users,email'],
        ];
        $request->validate($validationFields);
    }

    /**
     * save email  in database for specified $userId
     * @param {object} $request http request
     * @param {number} $userId
     */
    private function saveEmail(Request $request, $userId)
    {
        $item = $this->model::findOrFail($userId);
        $item->email = $request->new_email;
        $item->save();
    }

    /**
     * update user status in database
     * @param {number} $id user id
     * @param {number} $status status Id
     * @return {view} list view
     */
    private function setUserStatus($id, $status)
    {
        $user = $this->model::findOrFail($id);
        if ($user->id === auth()->user()->id) {
            return redirect()->route($this->routePath)->withErrors(['error' => __($this->translationPrefix . 'OwnStatusChangeError')]);
        }

        $user->status = $status;
        $user->save();
        $message = '';
        switch ($status) {
            case UserStatus::Pending:$message = __($this->translationPrefix . 'DeactivateSuccess');
                break;
            case UserStatus::Active:$message = __($this->translationPrefix . 'ActivateSuccess');
                break;
            case UserStatus::Deleted:$message = __($this->translationPrefix . 'DeleteSuccess');
                break;
        }
        return redirect()->route($this->routePath)->with(['success' => $message]);
    }
}
