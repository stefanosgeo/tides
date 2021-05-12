<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClipRequest;
use App\Models\Series;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SeriesClipsController extends Controller
{

    /**
     *  Show clip form for a series
     *
     * @param Series $series
     * @return View
     * @throws AuthorizationException
     */
    public function create(Series $series): View
    {
        $this->authorize('edit', $series);

        return view('backend.seriesClips.create', compact('series'));
    }

    /**
     * Store a clip with a series id
     *
     * @param Series $series
     * @param StoreClipRequest $request
     * @return RedirectResponse
     */
    public function store(Series $series, StoreClipRequest $request): RedirectResponse
    {
        $clip = $series->addClip($request->validated());

        $request->session()->flash('clip_created_successfully', 'Clip created successfully');

        return redirect(route('clips.edit', $clip));
    }
}
