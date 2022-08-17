<?php
namespace App\Helpers;

class Menu
{
    public $menuSideItems = [
        ['type' => 'section', 'title' => 'Dashboard'],
        ['type' => 'menu', 'title' => 'Dashboard', 'permission' => 'dashboard',
            'icon' => 'design/penandruller.svg', 'route' => 'dashboard'],
        ['type' => 'section', 'title' => 'Account'],
        ['type' => 'menu', 'title' => 'Account', 'permission' => 'account',
            'icon' => 'general/user.svg', 'path' => 'account',
            'children' => [
                ['title' => 'Profile', 'permission' => 'profile', 'route' => 'profile'],
                ['title' => 'Settings', 'permission' => 'settings', 'route' => 'settings'],
            ],
        ],
        ['type' => 'section', 'title' => 'Administration'],
        ['type' => 'menu', 'title' => 'Administration', 'permission' => 'administration',
            'icon' => 'communication/group.svg', 'section_path' => 'administration',
            'children' => [
                ['title' => 'Users', 'permission' => 'users', 'route' => 'users'],
            ],
        ],
        ['type' => 'separator'],
        ['type' => 'section', 'title' => 'BalanceSheet'],
        ['type' => 'menu', 'title' => 'BalanceSheet', 'permission' => 'balancesheet',
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

    public function __construct()
    {
        $this->updateMenuSide($this->menuSideItems, '');
    }

    public function getMenuSide($requestUri)
    {
        $requestUri = $this->formatRequestUri($requestUri);
        $pathList = explode('/', $requestUri);
        $this->setSelectedMenu($this->menuSideItems, $pathList, 0);
        return $this->menuSideItems;
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
        $requestUri = preg_replace('/\/banks\/\d+/', '/banks', $requestUri);

        switch ($requestUri) {
            case 'users':
                $requestUri = 'administration/' . $requestUri;
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

    private function updateMenuSide(&$items, $parentPath)
    {
        $iconBasePath = 'media/theme/icons/duotone/';

        foreach ($items as &$menu) {
            $path = isset($menu['path']) && $menu['path'] ? $menu['path'] : '';
            if ($parentPath) {
                $path = $path ? $parentPath . '/' . $path : $parentPath;
            }
            if (isset($menu['title'])) {
                $menu['id'] = $menu['title'];
                $menu['title'] = __('menu.' . $menu['title']);
            }
            if (isset($menu['route'])) {
                $route = $parentPath ? $parentPath . '/' . $menu['route'] : $menu['route'];
                $menu['route_short'] = $menu['route'];
                $menu['route'] = route($route);
            }
            if (isset($menu['icon'])) {
                $menu['icon'] = $this->readSvg($iconBasePath . $menu['icon']);
            }
            if (isset($menu['children'])) {
                $this->updateMenuSide($menu['children'], $path);
            }
        }
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
}
