<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HealthController extends Controller
{
    public function index()
    {
        config()->set('session.driver', 'array');
        return response('ok');
    }
}
