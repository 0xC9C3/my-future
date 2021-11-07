<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PortfolioController extends Controller
{
    public function create(Request $request)
    {
        // check if template exists and is active, otherwise redirect to default

        // if everything was fine save everything to the session and return template index.html

        // return default view for invalid key / template
        return 'default';
    }
}
