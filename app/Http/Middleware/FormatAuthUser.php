<?php

namespace App\Http\Middleware;

use App\Helpers\FileUtils;
use App\Models\UserInfo;
use Closure;

class FormatAuthUser
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
        $userInfo = UserInfo::where('user_id', $userId)->select(['avatar', 'updated_at', 'user_id'])->first();

        $avatar = FileUtils::getUserAvatarUrl($userInfo, '40x40', 'user/picture');
        $authUser = (object) ['avatar' => $avatar];

        \View::share('authUser', $authUser);
        return $next($request);
    }
}
