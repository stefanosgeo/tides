<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Carbon;


class AdminDashboardController extends Controller
{
    /**
     * Dashboard for the logged in user
     *
     * @return View
     */
    public function __invoke(): View
    {
        $files = collect(Storage::disk('video_dropzone')->files())->map(function ($file){
            return [
                'date_modified' =>Carbon::createFromTimestamp(Storage::disk('video_dropzone')->lastModified($file))->format('Y-m-d h:i:s'),
                'name'  => $file
            ];
        });

        return view('backend.dashboard.index', [
            'clips' => auth()->user()->clips()->orderByDesc('updated_at')->limit(10)->get(),
            'files' => $files->sortByDesc('date_modified')
        ]);
    }
}
