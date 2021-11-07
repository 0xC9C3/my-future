<?php

namespace App\Http\Controllers;

use App\Mail\JobApplication;
use App\Models\Respondent;
use App\Models\Statistic;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class OverviewController extends Controller
{
    public function create(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        // collect statistics @TODO dont use getQueueableIds, dont use DB::raw, add pagination
        $statistics = Statistic::whereIn('application_id', $user->applications->getQueueableIds())
            ->select('action', \DB::raw('count(*) as sum'))
            ->groupBy('action')
            ->pluck('sum', 'action')
            ->all();

        $statisticList = Statistic::whereIn('application_id', $user->applications->getQueueableIds())
            ->orderByDesc('statistics.created_at')
            ->with('application.respondent')
            ->with('application.template')
            ->lazy();

        return Inertia::render('Dashboard/Overview', [
            'templates' => [
                'email' => $request->user()->templates()->where('type', 'email')->lazy(),
                'portfolio' => $request->user()->templates()->where('type', 'portfolio')->lazy(),
            ],
            'statistics' => [
                'sent' => $statistics['sent'] ?? 0,
                'viewed' => $statistics['viewed'] ?? 0,
                'clicked' => $statistics['clicked'] ?? 0,
                'list' => $statisticList
            ]
        ]);
    }
}
