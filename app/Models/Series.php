<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

class Series extends Model {

    use HasFactory, Slugable;

    protected $guarded = [];

    public function clips(): HasMany
    {
        return $this->hasMany(Clip::class);
    }

    public function path(): string
    {
        return "/series/{$this->slug}";
    }

    public function adminPath(): string
    {
        return "/admin/series/{$this->slug}";
    }

    public function getRouteKeyName(): string
    {
        return 'slug'; // TODO: Change the autogenerated stub
    }

    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Add a clip on a series
     *
     * @param array $attributes
     * @return Model
     */
    public function addClip($validated = []): Model
    {
        $validated = Arr::add($validated, 'owner_id', auth()->user()->id);

        $clip  = $this->clips()->create(Arr::except($validated, 'tags'));

        $clip->addTags(collect($validated['tags']));

        return $clip;
    }
}
