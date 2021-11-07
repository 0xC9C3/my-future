<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Respondent;
use App\Models\Statistic;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

const TRACKING_PIXEL = "\x89\x50\x4e\x47\x0d\x0a\x1a\x0a\x00\x00\x00\x0d\x49\x48\x44\x52\x00\x00\x00\x01\x00\x00\x00\x01\x01\x03\x00\x00\x00\x25\xdb\x56\xca\x00\x00\x00\x03\x50\x4c\x54\x45\x00\x00\x00\xa7\x7a\x3d\xda\x00\x00\x00\x01\x74\x52\x4e\x53\x00\x40\xe6\xd8\x66\x00\x00\x00\x0a\x49\x44\x41\x54\x08\xd7\x63\x60\x00\x00\x00\x02\x00\x01\xe2\x21\xbc\x33\x00\x00\x00\x00\x49\x45\x4e\x44\xae\x42\x60\x82";

class ApplicationController extends Controller
{
    public function create(Request $request)
    {
        return Inertia::render('Dashboard/Applications', [
            'applications' => $request->user()->applications()
                ->with('respondent')
                ->with('template')
                ->orderBy('created_at')
                ->lazy()
        ]);
    }

    public function createApplicationForm(Request $request)
    {
        return Inertia::render('Dashboard/Apply', [
            'templates' => [
                'email' => $request->user()->templates()->where('type', 'email')->lazy(),
                'portfolio' => $request->user()->templates()->where('type', 'portfolio')->lazy(),
            ],
        ]);
    }

    public function trackEmail(Request $request, string $key)
    {
        if (!$key) {
            return response(TRACKING_PIXEL, 200, [
                'Content-Type' => 'image/png'
            ]);
        }

        try {
            $auth = decrypt($key);
            $applicationId = $auth['application_id'];
            if (!$applicationId) {
                return response(TRACKING_PIXEL, 200, [
                    'Content-Type' => 'image/png'
                ]);
            }

            $application = Application::find($auth['application_id']);

            if (!$application) {
                return response(TRACKING_PIXEL, 200, [
                    'Content-Type' => 'image/png'
                ]);
            }

            // save new statistic entry if settings are enabled
            if ($application->user->setting->track_email_access) {
                $application->statistics()->create([
                    'action' => 'viewed',
                ]);
            }
        } catch (\Exception $ex) {}

        return response(TRACKING_PIXEL, 200, [
            'Content-Type' => 'image/png'
        ]);
    }

    public function apply(Request $request)
    {
        $request->validate([
            'respondent.name' => 'required|string|max:255',
            'respondent.email' => 'required|string|email|max:255',
            'respondent.active' => 'required|boolean',

            'portfolio.id' => 'required|integer|numeric',

            'email.send_email' => 'required|boolean',
            'email.subject' => 'exclude_if:email.send_email,false|required|string|max:255',
            'email.content' => 'exclude_if:email.send_email,false|required|string',
        ]);

        /** @var User $user */
        $user = $request->user();

        // create respondent if he does not yet exist
        /** @var Respondent $respondent */
        $respondent = $user->respondents()->updateOrCreate(
            [
                'email' => $request->respondent['email'],
            ],
            [
                'name' => $request->respondent['name'],
                'active' => !!$request->respondent['active'],
            ]
        );

        // create the application
        /** @var Application $application */
        $application = $user->applications()->create([
            'respondent_id' => $respondent->id,
            'template_id' => $request->portfolio['id'],
            'email_subject' => $request->email['subject'] ?? '',
            'email_content' => $request->email['content'] ?? '',
            'email_attach_portfolio' => $request->email['attach_portfolio'] ?? false,
        ]);

        // send an email
        if ($request->email['send_email']) {
            $application->sendMail();
        }

        return back();
    }

    public function statistics(Request $request, int $applicationId)
    {
        /** @var Application|null $application */
        $application = $request->user()->applications()->find($applicationId);

        return response()->json($application->statistics()->with([
            'application',
            'application.template',
            'application.respondent'
        ])->lazy());
    }

    public function delete(Request $request)
    {
        $request->validate([
            'application' => 'required|integer|numeric',
        ]);

        $application = $request->user()->applications()->find($request->application);
        $application->delete();
        return back();
    }

    public function resend(Request $request)
    {
        $request->validate([
            'application' => 'required|integer|numeric',
        ]);

        $application = $request->user()->applications()->find($request->application);
        $application->sendMail();
        return back();
    }
}
