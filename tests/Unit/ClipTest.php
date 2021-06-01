<?php


namespace Tests\Unit;

use App\Models\Asset;
use App\Models\Clip;
use App\Models\Comment;
use App\Models\Series;
use App\Models\User;
use Carbon\Carbon;
use Facades\Tests\Setup\FileFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Tests\TestCase;

class ClipTest extends TestCase
{

    use RefreshDatabase;

    protected Clip $clip;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clip = Clip::factory()->create();
    }

    /** @test */
    public function it_has_a_path(): void
    {
        $this->assertEquals('/clips/'.$this->clip->slug, $this->clip->path());
    }

    /** @test */
    public function it_has_a_admin_path(): void
    {
        $this->assertEquals('/admin/clips/'.$this->clip->slug, $this->clip->adminPath());
    }

    /** @test */
    public function it_has_a_slug_route(): void
    {
        $this->get($this->clip->path())->assertStatus(200);
    }

    /** @test */
    public function it_has_an_incremental_slug(): void
    {
        Clip::factory()->create(['title' => 'A test title', 'slug' => 'A test title']);

        $clip = Clip::factory()->create(['title' => 'A test title', 'slug' => 'A test title']);

        $this->assertSame('a-test-title-2', $clip->slug);
    }

    /** @test */
    public function it_has_a_unique_slug(): void
    {
        $clipA = Clip::factory()->create(['title' => 'A test title', 'slug' => 'A test title']);

        $clipB = Clip::factory()->create(['title' => 'A test title', 'slug' => 'A test title']);

        $this->assertNotEquals($clipA->slug, $clipB->slug);
    }

    /** @test */
    public function it_has_a_set_slug_function(): void
    {
        $this->assertEquals($this->clip->slug, Str::slug($this->clip->title));
    }

    /** @test */
    public function it_has_many_assets(): void
    {
        Asset::factory(2)->create(['clip_id' => $this->clip->id]);

        $this->assertEquals(2, $this->clip->assets()->count());
    }

    /** @test */
    public function it_has_many_comments(): void
    {
        Comment::factory(2)->create(['clip_id' => $this->clip->id]);

        $this->assertEquals(2, $this->clip->comments()->count());
    }

    /** @test */
    public function it_belongs_to_an_owner(): void
    {
        $this->assertInstanceOf(User::class, $this->clip->owner);
    }

    /** @test */
    public function it_can_add_an_asset(): void
    {
        Storage::fake('videos');

        $clipStoragePath = getClipStoragePath($this->clip);

        $fileNameDate = $this->clip->created_at->format('Y-m-d');

        $videoFile = FileFactory::videoFile();

        $asset = $this->clip->addAsset([
            'disk'               => 'videos',
            'original_file_name' => $videoFile->getClientOriginalName(),
            'path'               =>
                $path = $videoFile->storeAs($clipStoragePath,
                    $fileNameDate.'-'.$this->clip->slug.'.'.Str::of($videoFile->getClientOriginalName())->after('.'),
                    'videos'),
            'duration'           => FFMpeg::fromDisk('videos')->open($path)->getDurationInSeconds(),
            'width'              => FFMpeg::fromDisk('videos')->open($path)
                                        ->getVideoStream()
                                        ->getDimensions()
                                        ->getWidth(),
            'height'             => FFMpeg::fromDisk('videos')->open($path)
                                        ->getVideoStream()
                                        ->getDimensions()
                                        ->getHeight()
        ]);

        $this->assertInstanceOf(Asset::class, $asset);
    }

    /** @test */
    public function it_can_updates_its_poster_image(): void
    {
        $this->assertNull($this->clip->posterImage);

        $file = FileFactory::videoFile();

        $file->storeAs('thumbnails', $this->clip->id.'_poster.png');

        $this->clip->updatePosterImage();

        $this->assertEquals('1_poster.png', $this->clip->posterImage);

        Storage::disk('thumbnails')->delete($this->clip->id.'_poster.png');
    }

    /** @test */
    public function it_can_add_tags(): void
    {
        $this->clip->addTags(collect(['php', 'tides']));

        $this->assertEquals(2, $this->clip->tags()->count());
    }

    /** @test */
    public function it_can_fetch_a_collection_of_previous_and_next_clip_models_if_clip_belongs_to_a_series(): void
    {
        $series = Series::factory()->create();

        Clip::factory()->create([
            'title' => 'first clip',
            'series_id' => $series->id,
            'episode'   => 1,
        ]);

        $secondClip = Clip::factory()->create([
            'title' => 'second clip',
            'series_id' => $series->id,
            'episode'   => 2,
        ]);

        Clip::factory()->create([
            'title' => 'third clip',
            'series_id' => $series->id,
            'episode'   => 3,
        ]);

        $this->assertInstanceOf(Collection::class, $secondClip->previousNextClipCollection());
        $this->assertInstanceOf(Clip::class, $secondClip->previousNextClipCollection()->get('previous'));
        $this->assertInstanceOf(Clip::class, $secondClip->previousNextClipCollection()->get('next'));
    }
}
