<?php

namespace App\Http\Controllers\Users;

use App\Helpers\ExportUtils;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use App\Models\UsersPermission;
use Illuminate\Http\Request;
use PDF;

/**
 * class which handles the list / create / update / delete of roles
 */
class UserPermissionsController extends Controller
{
    protected $viewPath = 'users/permissions';
    protected $routePath = 'users/permissions';
    protected $translationPrefix = 'permission.';
    protected $model = 'App\Models\UsersPermission';
    private $updateFields; // fields that will be updated on save
    private $selectFields;

    public function __construct()
    {
    }

    /**
     * role list index
     * @return {view} list view
     */
    public function index($userId = null)
    {
        $user = User::select(['id', 'first_name', 'last_name', 'email'])->findOrFail($userId);
        $page = (object) ['title' => __($this->translationPrefix . 'UserPermisisons'),
            'name' => __($this->translationPrefix . 'EditUserPermissions') . ': ' . $user->first_name . ' ' . $user->last_name . ' (' . $user->email . ')',
            'route' => '',
            'routeSave' => route($this->routePath . '/update', ['id' => $userId]),
            'routePath' => $this->routePath, 'translationPrefix' => $this->translationPrefix, 'viewPath' => $this->viewPath];
        $breadcrumbPath = $this->routePath;
        $permissionsList = $this->getUserPermissions($userId);
        return view($this->viewPath . '.list', compact('page', 'breadcrumbPath', 'userId', 'permissionsList'));
    }

    /**
     * get a list with the permissions of the user
     * @param {number} $userId user for which to get permissions
     * @return {array} list of permissions : [{ id, name, active }]
     */
    private function getUserPermissions($userId)
    {
        $permissionList = Permission::select(['id', 'name', 'slug'])->get()->toArray();

        // get user permissions
        $userPermissions = UsersPermission::where('user_id', $userId)->get();
        $userPermissionsIndexed = [];
        if (count($userPermissions) > 0) {
            foreach ($userPermissions as $userPermission) {
                $userPermissionsIndexed[$userPermission->permission_id] = 1;
            }
        }
        $userPermissionsList = [];
        // set the permissions active status
        foreach ($permissionList as $permission) {
            $active = isset($userPermissionsIndexed[$permission['id']]) ? 1 : 0;
            $permissionItem = (object) ['id' => $permission['id'], 'name' => $permission['name'], 'active' => $active];
            array_push($userPermissionsList, $permissionItem);
        };

        return $userPermissionsList;
    }

    /**
     * update user permissions in database
     * @param {object} $request http request
     * @param {number} $id user id for which to update
     * @return {view} edit view
     */
    public function update(Request $request, $id)
    {
        $this->savePermissions($request, $id);
        return redirect()->route($this->routePath, ['userId' => $id])->with(['success' => __('general.UpdatedSuccess')]);
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
     * update role in database, from provided request
     * @param {object} $request http request
     * @param {number} $userId id of the user for which to save the permissions
     */
    private function savePermissions(Request $request, $userId)
    {
        $permissions = $request->get('permissions');
        UsersPermission::where('user_id', $userId)->delete();
        if ($permissions !== null) {
            $data = [];
            foreach ($permissions as $permission) {
                array_push($data, ['user_id' => $userId, 'permission_id' => $permission]);
            }
            \DB::table('users_permissions')->insert($data);
        }
    }

    /** functions used to create / update role - END */

}
