<?php


namespace Tests\Feature\Frontend;

use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\SeriesFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomePageTest extends TestCase {

    use RefreshDatabase, WithFaker;

    /** @test */
    public function should_show_project_name(): void
    {
        $this->get(route('home'))->assertSee('Tides');
    }

    /** @test */
    public function it_has_a_language_switcher(): void
    {
        $this->get(route('home'))->assertSee('EN')->assertSee('DE');
    }

    /** @test */
    public function it_changes_portal_language(): void
    {
        $this->followingRedirects()->get('/set_lang/de');

        $this->get(route('home'))->assertSee('Letzte Videoaufnahmen');
    }

    /** @test */
    public function it_does_not_display_series_with_clips_without_assets(): void
    {
        $series = SeriesFactory::withClips(1)->create();

        $this->get(route('home'))->assertDontSee($series->title);
    }

    /** @test */
    public function it_displays_latest_series_with_clips_that_have_assets(): void
    {
        $series = SeriesFactory::create();

        $clip = ClipFactory::withAssets(1)->create();

        $series->clips()->save($clip);

        $this->get(route('home'))->assertSee($series->title);
    }

    /** @test */
    public function it_should_not_display_series_that_is_not_public(): void
    {
        $series = SeriesFactory::create();

        $clip = ClipFactory::withAssets(1)->create();

        $series->clips()->save($clip);

        $this->get(route('home'))->assertSee($series->title);

        $series->isPublic = false;

        $series->save();

        $this->get(route('home'))->assertDontSee($series->title);
    }

    /** @test */
    public function it_does_not_display_clips_without_assets(): void
    {
        $clip = ClipFactory::create();

        $this->get(route('home'))->assertDontSee($clip->title);
    }

    /** @test */
    public function it_does_not_display_clips_that_belong_to_a_series(): void
    {
        $series = SeriesFactory::withClips(1)->create();

        $this->get(route('home'))->assertDontSee($series->clips()->first()->title);
    }

    /** @test */
    public function it_displays_clips_with_video_assets(): void
    {
        $clip = ClipFactory::withAssets(1)->create();

        $this->get(route('home'))->assertSee($clip->title);
    }

    /** @test */
    public function it_should_not_display_clips_that_are_not_public(): void
    {
        $clip = ClipFactory::withAssets(1)->create();

        $this->get(route('home'))->assertSee($clip->title);

        $clip->isPublic = false;

        $clip->save();

        $this->get(route('home'))->assertDontSee($clip->title);
    }
}
