<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    protected $translationPrefix = 'dashboard.';

    public function index()
    {
        $start = microtime(true);
        $page = (object) ['title' => __('menu.Dashboard'), 'name' => __('menu.Dashboard'), 'route' => '',
            'translationPrefix' => $this->translationPrefix];
        $breadcrumbPath = 'dashboard';
        $time = microtime(true) - $start;
        $data = $this->getStats();
        $isAdmin = auth()->user()->hasRole('admin');

        return view('dashboard.dashboard', compact('page', 'time', 'breadcrumbPath', 'data', 'isAdmin'));
    }

    private function getStats()
    {
        $data = (object) [];
        $data->pendingUsersCount = User::where('status', 0)->count();
        $data->activeUsersCount = User::where('status', 1)->count();

        return $data;
    }
}
