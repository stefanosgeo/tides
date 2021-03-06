<?php

namespace Tests\Unit;

use App\Models\Clip;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    private Comment $comment;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->comment = Comment::factory()->create();
    }
    /** @test */
    public function it_belongs_to_a_clip(): void
    {
        $this->assertInstanceOf(Clip::class,$this->comment->clip);
    }

    /** @test */
    public function it_belongs_to_an_owner(): void
    {
        $this->assertInstanceOf(User::class, $this->comment->owner);
    }
}
