<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Exception;
use App\Http\Controllers\Controller;
use Socialite;

class Auth0LoginController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('auth0')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('auth0')->user();
        } catch (Exception $e) {
            return redirect()->to('auth/auth0');
        }

        $authUser = $this->findOrCreateUser($user);

        auth()->login($authUser, true);

        return redirect()->intended('/');
    }

    public function logout()
    {
        auth()->logout();

        return redirect('/');
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $user
     * @return User
     */
    private function findOrCreateUser($user)
    {
        if ($authUser = User::where('external_id', $user->id)->where('type', 'auth0')->first()) {
            return $authUser;
        }

        return User::create([
            'name' => $user->name,
            'email' => $user->email,
            'external_id' => $user->id,
            'type' => 'auth0'
        ]);
    }
}
