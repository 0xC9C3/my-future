<?php

namespace App\Http\Middleware;

use App\Models\Application;
use Closure;
use Illuminate\Http\Request;

class AuthenticatePortfolio
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // if route exists serve it instead
        $routes = \Route::getRoutes();
        try {
            $route = $routes->match($request);
            if (!($route->getName() === 'portfolio.view')) {
                return $next($request);
            }
        }
        catch (\Exception $ex) {
            // route does not exist, so we attempt to serve the portfolio
        }

        // serve files from template folder if session has template related information
        // or check if key query parameter exists and write information to session

        $key = $request->query('key');
        if ($key) {
            try {
                $auth = decrypt($key);
                // if it is a link sent to a respondent query for the template and respondent status,
                // don't log anything in case it is an authenticated user testing the portfolio
                if (isset($auth['application_id']) && !$request->user()) {
                    // check if respondent is set and active
                    $application = Application::find($auth['application_id']);
                    $respondent = $application->respondent;

                    if (!$application || !$respondent->active || !$application->template_id) {
                        return redirect()->route('error.key-disabled');
                    }

                    if ($application->user->setting->track_web_access) {
                        $application->statistics()->create([
                            'action' => 'clicked',
                        ]);
                    }

                    \Session::put('template', [
                        'template_id' => $application->template_id,
                        'user_id' => $application->user_id,
                    ]);
                }
                else {
                    // it is a preview key, so we just store template relevant data
                    \Session::put('template', $auth);
                }
                \Session::save();

                // we redirect here without a key to prevent triggering the viewed event
                return redirect($request->fullUrlWithQuery(['key' => null]));
            } catch (\Exception $ex) {
                return redirect()->route('error.key-invalid');
            }
        }

        $template = \Session::get('template');

        if ($template && $template['template_id'] && $template['user_id']) {

            // @TODO evaluate safety of $request->path()

            // return index.html on base path
            $requestPath = $request->path() === '/' ? 'index.html' : $request->path();
            $path = storage_path('app/templates/' . $template['user_id'] . '/' . $template['template_id'] . '/') . $requestPath;

            if (!file_exists($path)) {
                abort(404);
            }

            return response()->file($path);
        }

        return redirect()->route('error.key-and-session-missing');
    }
}
