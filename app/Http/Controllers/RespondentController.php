<?php

namespace App\Http\Controllers;

use App\Models\Respondent;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RespondentController extends Controller
{
    public function create(Request $request)
    {
        return Inertia::render('Dashboard/Respondents', [
            'respondents' => $request->user()->respondents()->orderBy('created_at')->lazy()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'active' => 'required|boolean',
        ]);

        /** @var User $user */
        $user = $request->user();

        $user->respondents()->firstOrCreate(
            [
                'email' => $request->email,
            ],
            [
                'name' => $request->name,
                'active' => !!$request->active,
            ]
        );

        return back();
    }

    public function delete(Request $request)
    {
        $request->validate([
            'respondent' => 'required|integer|numeric',
        ]);

        $respondent = $request->user()->respondents()->find($request->respondent);
        $respondent->delete();
        return back();
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'respondent' => 'required|integer|numeric',
        ]);

        $respondent = $request->user()->respondents()->find($request->respondent);
        $respondent->active = !$respondent->active;
        $respondent->save();
        return back();
    }
}
