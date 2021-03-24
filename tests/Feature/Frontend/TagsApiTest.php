<?php

namespace Tests\Feature\Frontend;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagsApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_search_tags()
    {
        Tag::factory()->create(['name'=>'algebra']);

        $response = $this->get('/api/tags/?query=algebra');

        $response->assertJson([
                ["id" => 1,"name" => 'algebra']
            ]);
    }
}
