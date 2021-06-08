<?php

namespace App\Models;

use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Series extends BaseModel
{

    use Slugable;

    /**
     * A series can have many clips
     *
     * @return HasMany
     */
    public function clips(): HasMany
    {
        return $this->hasMany(Clip::class);
    }

    /**
     * Series public url
     *
     * @return string
     */
    public function path(): string
    {
        return "/series/{$this->slug}";
    }

    /**
     * Series admin edit url
     *
     * @return string
     */
    public function adminPath(): string
    {
        return "/admin/series/{$this->slug}";
    }

    /**
     * Route key should be slug instead of id
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug'; // TODO: Change the autogenerated stub
    }

    /**
     * A series belongs to a user
     *
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Add a clip on a series
     *
     * @param array $validated
     * @return Clip
     */
    public function addClip(array $validated = []): Clip
    {
        $validated = Arr::add($validated, 'owner_id', auth()->user()->id);

        $clip  = $this->clips()->create(Arr::except($validated, 'tags'));

        $clip->addTags(collect($validated['tags']));

        return $clip;
    }

    /**
     * Updates opencast series id in series table
     *
     * @param Response $response
     */
    public function updateOpencastSeriesId(Response $response): void
    {
        if (!empty($response->getHeaders())) {
            $this->opencast_series_id = Str::afterLast($response->getHeaders()['Location'][0], 'api/series/');

            $this->update();
        }
    }
}
