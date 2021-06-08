<?php

namespace App\Jobs;

use App\Models\Clip;
use App\Services\OpencastService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IngestVideoFileToOpencast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private Clip $clip, private string $videoFile)
    {
    }

    /**
     * Ingest the given video file to Opencast server
     *
     * @return void
     * @throws FileNotFoundException
     */
    public function handle(OpencastService $opencastService): void
    {
        $opencastService->ingestMediaPackage($this->clip, $this->videoFile);
    }
}
