<?php

namespace App\Providers;

use App\Events\AssetDeleted;
use App\Listeners\DeleteAssetFile;
use App\Models\Clip;
use App\Models\Series;
use App\Observers\ClipObserver;
use App\Observers\SeriesObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class   => [
            SendEmailVerificationNotification::class,
        ],
        AssetDeleted::class => [
            DeleteAssetFile::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Series::observe(SeriesObserver::class);
        Clip::observe(ClipObserver::class);
    }
}
