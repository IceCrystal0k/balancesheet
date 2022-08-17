<?php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

$pageList = ['dashboard', 'account', 'users',
    'balancesheet' => ['targets', 'daily-balance', 'monthly-balance', 'statistics'],
];
foreach ($pageList as $pageId => $pageItems) {
    if (!is_array($pageItems)) {
        Breadcrumbs::for($pageItems, function (BreadcrumbTrail $trail, $page) use ($pageItems) {
            if (isset($page->routeCreate) || isset($page->routeSave)) {
                $trail->push(__('general.List'), route($pageItems));
            }
            if ($page->route) {
                $trail->push($page->name, $page->route);
            }
        });
    } else {
        foreach ($pageItems as $modulePageId) {
            Breadcrumbs::for($pageId . '/' . $modulePageId, function (BreadcrumbTrail $trail, $page) use ($pageId, $modulePageId) {
                $trail->push(__('menu.' . ucfirst($modulePageId)));
                if (isset($page->customRoute)) {
                    $trail->push(__('general.List'), $page->customRoute);
                } else if (isset($page->isList) || isset($page->routeCreate) || isset($page->routeSave)) {
                    $trail->push(__('general.List'), route($pageId . '/' . $modulePageId));
                }
                if ($page->route) {
                    $trail->push($page->name, $page->route);
                }
            });
        }
    }
}

// $pageList = ['dashboard', 'account', 'users', 'pages', 'pagetexts', 'categories', 'mediafiles', 'slider', 'articles'];
// foreach ($pageList as $pageId) {
//     Breadcrumbs::for($pageId, function (BreadcrumbTrail $trail, $page) use ($pageId) {
//         if (isset($page->routeCreate) || isset($page->routeSave)) {
//             $trail->push(__('general.List'), route($pageId));
//         }
//         if ($page->route) {
//             $trail->push($page->name, $page->route);
//         }
//     });
// }
