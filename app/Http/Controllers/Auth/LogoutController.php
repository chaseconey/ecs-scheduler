<?php

namespace App\Http\Controllers\Auth;

class LogoutController
{

    public function logout()
    {
        auth()->logout();

        return redirect()->to('/');
    }

}
