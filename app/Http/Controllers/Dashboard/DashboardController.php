<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{

    public function index()
    {
        $start = microtime(true);
        $page = (object) ['title' => __('menu.Dashboard'), 'name' => __('menu.Dashboard'), 'route' => ''];
        $breadcrumbPath = 'dashboard';
        $time = microtime(true) - $start;
        $data = $this->getStats();

        return view('dashboard.dashboard', compact('page', 'time', 'breadcrumbPath', 'data'));
    }

    private function getStats()
    {
        $data = (object) [];
        $data->pendingUsersCount = User::where('status', 0)->count();
        $data->activeUsersCount = User::where('status', 1)->count();

        return $data;
    }
}
