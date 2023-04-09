<?php
namespace App\Helpers;

class Menu
{
    public $menuSideItems = [
        ['type' => 'section', 'title' => 'Dashboard', 'permission' => 'dashboard'],
        ['type' => 'menu', 'title' => 'Dashboard', 'permission' => 'dashboard',
            'icon' => 'design/penandruller.svg', 'route' => 'dashboard'],
        ['type' => 'section', 'title' => 'Account', 'permission' => ['profile', 'settings']],
        ['type' => 'menu', 'title' => 'Account', 'permission' => ['profile', 'settings'],
            'icon' => 'general/user.svg', 'path' => 'account',
            'children' => [
                ['title' => 'Profile', 'permission' => 'profile', 'route' => 'profile'],
                ['title' => 'Settings', 'permission' => 'settings', 'route' => 'settings'],
            ],
        ],
        ['type' => 'section', 'title' => 'Administration', 'role' => 'admin'],
        ['type' => 'menu', 'title' => 'Administration', 'role' => 'admin',
            'icon' => 'communication/group.svg', 'section_path' => 'administration',
            'children' => [
                ['title' => 'Roles', 'route' => 'roles'],
                ['title' => 'Permissions', 'route' => 'permissions'],
                ['title' => 'Users', 'route' => 'users'],
            ],
        ],
        ['type' => 'separator'],
        ['type' => 'section', 'title' => 'BalanceSheet', 'permission' => ['targets', 'monthly-balance', 'daily-balance', 'statistics']],
        ['type' => 'menu', 'title' => 'BalanceSheet', 'permission' => ['targets', 'monthly-balance', 'daily-balance', 'statistics'],
            'icon' => 'finance/finance.svg', 'path' => 'balancesheet',
            'children' => [
                ['title' => 'Targets', 'permission' => 'targets', 'route' => 'targets'],
                ['title' => 'MonthlyBalance', 'permission' => 'monthly-balance', 'route' => 'monthly-balance'],
                ['title' => 'DailyBalance', 'permission' => 'daily-balance', 'route' => 'daily-balance'],
                ['title' => 'Statistics', 'permission' => 'statistics', 'route' => 'statistics'],
            ],
        ],
        ['type' => 'separator'],

    ];

    private $visibleMenuItems = [];

    public function __construct()
    {
        $this->visibleMenuItems = $this->getVisibleMenuItems($this->menuSideItems, '');
    }

    public function getMenuSide($requestUri)
    {
        $requestUri = $this->formatRequestUri($requestUri);
        $pathList = explode('/', $requestUri);
        $this->setSelectedMenu($this->visibleMenuItems, $pathList, 0);
        return $this->visibleMenuItems;
    }

    /**
     * remove trailing '/' and add parent section_path for menus which don't have a path
     * also point to parent menu for pages which don't have their own menu
     */
    private function formatRequestUri($requestUri)
    {
        $requestUri = substr($requestUri, 1); // remove trailing /
        // remove edit, create from path
        $requestUri = preg_replace('/\/edit\/.+/', '', $requestUri);
        $requestUri = preg_replace('/\/create\/\d+/', '', $requestUri);
        $requestUri = preg_replace('/\/create/', '', $requestUri);

        // remove pages with custom parents
        $requestUri = preg_replace('/\/permissions\/\d+/', '/permissions', $requestUri);

        switch ($requestUri) {
            case 'permissions':
            case 'roles':
            case 'users':
                $requestUri = 'administration/' . $requestUri;
                break;
            case 'users/permissions':
                $requestUri = 'administration/users';
                break;
            case 'roles/permissions':
                $requestUri = 'administration/roles';
                break;
        }
        return $requestUri;
    }

    private function setSelectedMenu(&$items, &$pathList, $pathIndex)
    {
        $pathListCount = count($pathList) - 1;

        $path = $pathList[$pathIndex];
        foreach ($items as &$menu) {
            if ($pathIndex == $pathListCount) {
                if (isset($menu['route_short']) && $menu['route_short'] == $path) {
                    $menu['menu_css'] = 'active';
                    break;
                }
            } else {
                if ((isset($menu['path']) && $menu['path'] == $path) ||
                    (isset($menu['section_path']) && $menu['section_path'] == $path)
                ) {
                    $menu['menu_css'] = 'show';
                    if (isset($menu['children'])) {
                        $this->setSelectedMenu($menu['children'], $pathList, $pathIndex + 1);
                    }
                    break;
                }
            }
        }

    }

    private function getVisibleMenuItems($items, $parentPath)
    {
        $iconBasePath = 'media/theme/icons/duotone/';
        $visibleMenuList = [];

        foreach ($items as $menu) {
            if (!$this->userHasPermission($menu)) {
                continue;
            }

            $path = isset($menu['path']) && $menu['path'] ? $menu['path'] : '';
            // create a new menu item
            $visibleMenu = ['path' => $path];
            if (isset($menu['type'])) {
                $visibleMenu['type'] = $menu['type'];
            }

            if (isset($menu['section_path'])) {
                $visibleMenu['section_path'] = $menu['section_path'];
            }

            if ($parentPath) {
                $path = $path ? $parentPath . '/' . $path : $parentPath;
            }
            if (isset($menu['title'])) {
                $visibleMenu['id'] = $menu['title'];
                $visibleMenu['title'] = __('menu.' . $menu['title']);
            }
            if (isset($menu['route'])) {
                $route = $parentPath ? $parentPath . '/' . $menu['route'] : $menu['route'];
                $visibleMenu['route_short'] = $menu['route'];
                $visibleMenu['route'] = route($route);
            }
            if (isset($menu['icon'])) {
                $visibleMenu['icon'] = $this->readSvg($iconBasePath . $menu['icon']);
            }
            if (isset($menu['children'])) {
                $visibleMenu['children'] = $this->getVisibleMenuItems($menu['children'], $path);
            }

            array_push($visibleMenuList, $visibleMenu);
        }
        return $visibleMenuList;
    }

    private function readSvg($path, $class = null)
    {
        $svg = new \DOMDocument();
        $svg->load(public_path($path));
        if ($class) {
            $svg->documentElement->setAttribute("class", $class);
        }
        $output = $svg->saveXML($svg->documentElement);
        return $output;
    }

    private function userHasPermission($menu)
    {
        $hasPermission = true;
        if (isset($menu['role']) && $menu['role']) {
            if (!auth()->user()->hasRole($menu['role'])) {
                $hasPermission = false;
            }
        } else if (isset($menu['permission']) && $menu['permission']) {
            $hasPermission = false;
            $requiredPermissions = $menu['permission'];
            if (is_array($requiredPermissions)) {
                foreach ($requiredPermissions as $permission) {
                    if (auth()->user()->can($permission)) {
                        $hasPermission = true;
                        break;
                    }
                }
            } else {
                $hasPermission = auth()->user()->can($requiredPermissions);
            }
        }

        return $hasPermission;
    }
}
