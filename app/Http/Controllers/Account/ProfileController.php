<?php

namespace App\Http\Controllers\Account;

use App\Helpers\FileUtils;
use App\Helpers\Form;
use App\Helpers\SelectUtils;
use App\Helpers\UserUtils;
use App\Http\Controllers\Controller;
use App\Mail\Account\AccountDelete;
use App\Models\User;
use App\Models\UserEmailToken;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Mail;

/**
 * class which handles the view and edit of authenticated user
 */
class ProfileController extends Controller
{
    protected $viewPath = 'account';
    protected $routePath = 'account/settings';
    protected $translationPrefix = 'account.';

    public function __construct()
    {

    }

    /**
     * user profile details page
     * @return {view} profile view
     */
    public function profile()
    {
        // add_measure('now', LARAVEL_START, microtime(true));
        $data = $this->getUserProfile();
        $page = (object) ['title' => __($this->translationPrefix . 'Account'), 'name' => __($this->translationPrefix . 'Profile'),
            'route' => route('account/profile'),
            'routePath' => $this->routePath, 'translationPrefix' => $this->translationPrefix, 'viewPath' => $this->viewPath];
        $breadcrumbPath = 'account';
        return view($this->viewPath . '.profile', compact('data', 'page', 'breadcrumbPath'));
    }

    /**
     * user profile edit page
     * @return {view} settings view
     */
    public function settings()
    {
        $data = $this->getUser();
        $editFields = ['first_name', 'last_name', 'currency', 'date_format', 'date_format_separator'];
        Form::updateModelFromRequest(request()->old(), $data, $editFields);

        $dateFormatSelectOptions = SelectUtils::getDateFormatSelectOptions($data->date_format);
        $dateFormatSeparatorSelectOptions = SelectUtils::getDateFormatSeparatorSelectOptions($data->date_format_separator);

        $userId = $data->user_id;

        $currencySelectOptions = SelectUtils::getCurrencySelectOptions($data->currency);
        $page = (object) ['title' => __($this->translationPrefix . 'Account'), 'name' => 'Settings', 'route' => 'settings',
            'routePath' => $this->routePath, 'translationPrefix' => $this->translationPrefix, 'viewPath' => $this->viewPath,
        ];
        $breadcrumbPath = 'account';
        return view($this->viewPath . '.settings', compact('data', 'page', 'currencySelectOptions', 'breadcrumbPath',
            'dateFormatSelectOptions', 'dateFormatSeparatorSelectOptions'));
    }

    /**
     * update user in database
     * @param {object} $request http request
     * @return {view} edit view
     */
    public function updateProfile(Request $request)
    {
        $this->validateProfileUpdateRequest($request);
        $this->saveUser($request);
        $userInfo = $this->saveUserInfo($request);
        UserUtils::updateUserSetting(auth()->user()->id, $userInfo);
        return redirect()->route($this->routePath)->with(['success' => __($this->translationPrefix . 'ProfileUpdatedSuccess')]);
    }

    /**
     * get curent user profile
     * @return {object} user model
     */
    private function getUserProfile()
    {
        $data = User::with(['userInfo'])
            ->findOrFail(auth()->user()->id);

        Form::copyObjectAttributes($data->userInfo, $data, ['user_id', 'avatar', 'company', 'phone', 'website', 'communication', 'marketing']);
        $this->formatUserData($data);

        return $data;
    }

    /**
     * get current user
     * @return {object} user model
     */
    private function getUser()
    {
        $data = User::leftJoin('user_infos', 'users.id', '=', 'user_infos.user_id')
            ->select(['users.id', 'user_id', 'first_name', 'last_name', 'email', 'google_id', 'fb_id',
                'avatar', 'currency', 'date_format', 'date_format_separator', 'user_infos.updated_at'])
            ->findOrFail(auth()->user()->id);

        $this->formatUserData($data);

        return $data;
    }

    /**
     * format user for display
     * @param {object} $data user model
     */
    private function formatUserData($data)
    {
        $data->name = $data->first_name . ' ' . $data->last_name;
        $data->communication = $data->communication ? json_decode($data->communication) : null;
        $data->communication_display = $data->communication ? implode(', ', array_keys((array) $data->communication)) : '';

        $data->avatar_card = FileUtils::getUserAvatarUrl($data, '160x160', 'user/picture');
        $data->avatar_edit = FileUtils::getUserAvatarUrl($data, '125x125', 'user/picture');
        $data->hasAvatar = strpos($data->avatar_card, 'blank.png') === false;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /** Save profile details - BEGIN */

    private function validateProfileUpdateRequest(Request $request)
    {
        $validationFields = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'date_format' => ['nullable', 'numeric', 'min:1', 'max:3'],
            'date_format_separator' => ['nullable', 'numeric', 'min:1', 'max:3'],
        ];
        $request->validate($validationFields);
    }

    private function saveUser(Request $request)
    {
        $userId = auth()->user()->id;
        $user = User::findOrFail($userId);
        $updateFields = ['first_name', 'last_name'];
        Form::updateModelFromRequest($request, $user, $updateFields);
        $user->save();
    }

    private function saveUserInfo(Request $request)
    {
        $userId = auth()->user()->id;
        $userInfo = UserInfo::where('user_id', $userId)->first();
        if (!$userInfo) {
            $userInfo = new UserInfo();
            $userInfo->user_id = $userId;
        }
        $updateFields = ['currency', 'date_format', 'date_format_separator'];
        Form::updateModelFromRequest($request, $userInfo, $updateFields);

        $storeOptions = ['newFileName' => 'avatar'];
        if ($request->avatar_remove === '1') {
            $storeOptions['removeFileName'] = $userInfo->avatar;
            // if avatar is removed, delete all cache file
            File::delete(File::glob(storage_path('app/public/account/user_' . $userId . '/avatar-*')));
        }
        $avatar = FileUtils::storeRequestFile($request, 'avatar', 'public/account/user_' . $userId . '/', $storeOptions);
        if ($avatar) {
            $userInfo->avatar = $avatar;
            // if avatar is changed, delete all cache files
            File::delete(File::glob(storage_path('app/public/account/user_' . $userId . '/avatar-*')));
        }

        $userInfo->save();

        return (object) ['currency' => $userInfo->currency, 'date_format' => $userInfo->date_format, 'date_format_separator' => $userInfo->date_format_separator];
    }

    /** Save profile details - END */

    /** Update password - BEGIN */
    public function updatePassword(Request $request)
    {
        $this->validatePasswordUpdateRequest($request);
        $this->savePassword($request);
        return redirect()->route($this->routePath)->with(['success' => __($this->translationPrefix . 'PasswordUpdatedSuccess')]);
    }

    private function validatePasswordUpdateRequest(Request $request)
    {
        $userPassword = auth()->user()->password;
        $validationFields = [
            'current_password' => ['required', 'string', 'max:255',
                function ($attribute, $value, $fail) use ($userPassword) {
                    if (!\Hash::check($value, $userPassword)) {
                        return $fail(__($this->translationPrefix . 'CurrentPasswordNotMatch'));
                    }
                },
            ],
            'new_password' => ['required', 'string', 'max:255', 'min:8'],
            'password_confirmation' => ['required', 'string', 'same:new_password'],
        ];
        $request->validate($validationFields);
    }

    private function savePassword(Request $request)
    {
        $userId = auth()->user()->id;
        $user = User::findOrFail($userId);
        $user->password = \Hash::make($request->new_password);
        $user->save();
    }
    /** Update password details - END */

    /** Update connected accounts - BEGIN */
    public function updateConnectedAccounts(Request $request)
    {
        $userId = auth()->user()->id;
        $user = User::findOrFail($userId);
        $updateUser = false;
        if ($user->google_id && !$request->has('google_connection')) {
            $updateUser = true;
            $user->google_id = null;
        }
        if ($user->fb_id && !$request->has('facebook_connection')) {
            $updateUser = true;
            $user->fb_id = null;
        }
        if ($updateUser) {
            $user->save();
        }
        return redirect()->route($this->routePath)->with(['success' => __($this->translationPrefix . 'ConnectedAccountsUpdatedSuccess')]);
    }
    /** Update connected accounts - END */

    /** Delete account - BEGIN */
    public function deleteAccount(Request $request)
    {
        $validationFields = [
            'confirm_delete' => ['required'],
        ];
        $request->validate($validationFields);

        $userId = auth()->user()->id;
        $user = User::findOrFail($userId);

        $accessToken = hash('sha256', $plainTextToken = Str::random(40));

        $this->createDeleteEntry($userId, $user->email, $accessToken);

        $message = (object) ['name' => $user->first_name . ' ' . $user->last_name,
            'website' => url('/'),
            'deleteLink' => route('confirm-delete-account', ['token' => $accessToken]),
            'subject' => 'Account delete confirmation',
        ];

        Mail::to($user->email)->send(new AccountDelete($message));

        return redirect()->route($this->routePath)->with(['success' => __($this->translationPrefix . 'AccountDeletedMailSent')]);
    }

    private function createDeleteEntry($userId, $email, $accessToken)
    {
        $userEmailToken = UserEmailToken::find($email);
        if ($userEmailToken) {
            if ($userEmailToken->created_at) {
                $requestDate = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $userEmailToken->created_at);
                $now = \Carbon\Carbon::now();
                $diffMinutes = $requestDate->diffInMinutes($now);
                if ($diffMinutes < 5) {
                    return redirect()->route($this->routePath)->withErrors(['error' => __($this->translationPrefix . 'AccountDeletedMailFrequency')]);
                }
            }
        } else {
            $userEmailToken = new UserEmailToken();
        }

        $userEmailToken->email = $email;
        $userEmailToken->action = 'delete-account';
        $userEmailToken->token = $accessToken;
        $userEmailToken->created_at = \Carbon\Carbon::now();
        $userEmailToken->save();

    }

    public function confirmDeleteAccount(Request $request)
    {
        $data = (object) ['css' => 'text-danger', 'title' => __($this->translationPrefix . 'AccountDeletedErrorTitle'), 'info' => __($this->translationPrefix . 'AccountDeletedErrorInfo')];
        if (!$request->route()->parameter('token')) {
            return view('auth.account-deleted', compact('data'));
        }
        $token = $request->token;
        $userDelete = UserEmailToken::where('token', $token)->where('action', 'delete-account')->first();
        $validRequest = false;
        if ($userDelete && $userDelete->created_at) {
            $requestDate = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $userDelete->created_at);
            $now = \Carbon\Carbon::now();
            $diffHours = $requestDate->diffInHours($now);
            if ($diffHours <= 24) {
                $validRequest = true;
            }
        }

        if ($validRequest) {
            $data = (object) ['css' => 'text-success', 'title' => __($this->translationPrefix . 'AccountDeletedSuccessTitle'), 'info' => __($this->translationPrefix . 'AccountDeletedSuccessInfo')];
            $userDelete->delete();
            // also delete user
            $this->deleteUserAccount($userDelete->email);
        }

        return view('auth.account-deleted', compact('data'));
    }

    public function deleteUserAccount($email)
    {
        $user = User::where('email', $email)->first();
        if ($user) {
            $userInfo = UserInfo::where('user_id', $user->id)->first();
            if ($userInfo) {
                $userInfo->delete();
            }
            $user->delete();
        }
    }
    /** Delete account - END */
}
