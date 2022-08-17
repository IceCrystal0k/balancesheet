<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback()
    {
        try {

            $user = Socialite::driver('google')->user();
            $finduser = User::where('google_id', $user->id)->first();

            if ($finduser) {
                Auth::login($finduser);
                return redirect()->intended('dashboard');
            } else {
                // check for existing user, maybe it logged in with facebook account before
                $userModel = User::where('email', $user->email)->first();
                if ($userModel) {
                    $userModel->google_id = $user->id;
                    $userModel->save();
                    Auth::login($userModel);
                } else {
                    $name = $this->extractNameFromEmail($user->email);
                    $password = Hash::make(Str::random(8));
                    $newUser = User::create([
                        'first_name' => $name['firstName'],
                        'last_name' => $name['lastName'],
                        'email' => $user->email,
                        'google_id' => $user->id,
                        'password' => $password,
                    ]);
                    $newUser->sendEmailVerificationNotification();
                    Auth::login($newUser);
                }
                return redirect()->intended('dashboard');
            }

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {

            $user = Socialite::driver('facebook')->user();
            $isUser = User::where('fb_id', $user->id)->first();

            if ($isUser) {
                Auth::login($isUser);
                return redirect('/dashboard');
            } else {
                // check for existing user, maybe it logged in with google account before
                $userModel = User::where('email', $user->email)->first();
                if ($userModel) {
                    $userModel->fb_id = $user->id;
                    $userModel->save();
                    Auth::login($userModel);
                } else {
                    $name = $this->extractNameFromFacebook($user->name);
                    $password = Hash::make(Str::random(8));
                    // make use of $user->avatar if needed
                    $createUser = User::create([
                        'first_name' => $name['firstName'],
                        'last_name' => $name['lastName'],
                        'email' => $user->email,
                        'fb_id' => $user->id,
                        'password' => $password,
                    ]);
                    $createUser->sendEmailVerificationNotification();
                    Auth::login($createUser);
                }

                return redirect('/dashboard');
            }

        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
    }

    private function extractNameFromEmail($email)
    {
        $userName = substr($email, 0, strpos($email, '@'));
        $symbols = ['.', '_', '-'];
        $firstName = $userName;
        $lastName = $userName;
        foreach ($symbols as $symbol) {
            $symbolPos = strpos($userName, $symbol);
            if ($symbolPos > 0) {
                $firstName = substr($userName, 0, $symbolPos);
                $lastName = substr($userName, $symbolPos + 1);
                break;
            }
        }
        return ['firstName' => $firstName, 'lastName' => $lastName];
    }

    private function extractNameFromFacebook($userName)
    {
        $symbols = [' ', '.', '_', '-'];
        $firstName = $userName;
        $lastName = $userName;
        foreach ($symbols as $symbol) {
            $symbolPos = strpos($userName, $symbol);
            if ($symbolPos > 0) {
                $firstName = substr($userName, 0, $symbolPos);
                $lastName = substr($userName, $symbolPos + 1);
                break;
            }
        }
        return ['firstName' => $firstName, 'lastName' => $lastName];
    }
}