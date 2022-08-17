<?php

namespace App\Http\Middleware;

use App\Helpers\Menu;
use Closure;

class MenuSide
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        $userId = $user->id;

        $menu = new Menu();
        $menuSide = $menu->getMenuSide($request->getRequestUri());

        \View::share('menuSide', $menuSide);
        return $next($request);
    }

}