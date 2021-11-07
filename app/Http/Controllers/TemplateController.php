<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\Browsershot\Browsershot;

class TemplateController extends Controller
{
    /**
     * Display the settings view.
     *
     * @return \Inertia\Response
     */
    public function create(Request $request)
    {
        return Inertia::render('Dashboard/Templates', [
            'templates' => [
                'email' => $request->user()->templates()->where('type', 'email')->lazy(),
                'portfolio' => $request->user()->templates()->where('type', 'portfolio')->lazy(),
            ]
        ]);
    }

    public function download(Request $request, int $templateId)
    {
        $template = $request->user()->templates()->find($templateId);

        // https://stackoverflow.com/a/4914807
        $zip_file = storage_path('app/tmp/' .  time() . $template->user_id . $template->name . '.zip');
        \File::delete($zip_file);
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $path = $template->path();
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        foreach ($files as $name => $file)
        {
            if (!$file->isDir()) {
                $filePath     = $file->getRealPath();

                $relativePath = substr($filePath, strlen($path));

                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
        return response()->download($zip_file)->deleteFileAfterSend();
    }

    public function delete(Request $request)
    {
        $request->validate([
            'template' => 'required|integer|numeric',
        ]);

        $template = $request->user()->templates()->find($request->template);
        $template->delete();
        return back();
    }

    public function update(Request $request)
    {
        $request->validate([
            'template' => 'required|integer|numeric',
        ]);

        $template = $request->user()->templates()->find($request->template);
        $template->deleteStorage();
        $zip = new \ZipArchive();
        $zip->open($request->file('file')->getPathname());
        $zip->extractTo($template->path());

        return back();
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => [
                'required',
                Rule::in(['email', 'portfolio']),
            ],
            'name' => 'required|string|max:255',
        ]);

        $template = $request->user()->templates()->create([
           'type' => $request->type,
           'name' => $request->name,
        ]);

        $zip = new \ZipArchive();
        $zip->open($request->file('template')->getPathname());
        $zip->extractTo($template->path());

        return back();
    }

    public function export(Request $request, int $templateId)
    {
        $template = $request->user()->templates()->find($templateId);
        $path = $template->export();

        return response()->download($path)->deleteFileAfterSend();
    }
}
