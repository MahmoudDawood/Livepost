<?php

namespace Tests\Feature\Api\Comment;

use Tests\TestCase;
use App\Models\Comment;
use Illuminate\Support\Facades\Event;

use App\Events\Models\Comment\CommentCreated;
use App\Events\Models\Comment\CommentDeleted;
use App\Events\Models\Comment\CommentUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentApiTest extends TestCase
{
    use RefreshDatabase;

    protected $uri = '/api/v1/comments/';

    public function test_index() {
        $comments = Comment::factory(10)->create();
        $commentIds = $comments->map(function($post) {
            return $post->id;
        });

        $response = $this->json('get', $this->uri);

        $response->assertStatus(200);
        $data = $response->json('data');

        collect($data)->each(function($comment) use($commentIds) {
            $this->assertTrue(in_array($comment['id'], $commentIds->toArray()));
        });
    }

    public function test_show() {
        $dummyComment = Comment::factory()->create();

        $response = $this->json('get',  $this->uri . $dummyComment->id);
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertSame(data_get($data, 'id'), $dummyComment->id, 'Response ID does not match model id.');
    }

    public function test_create() {
        Event::fake();
        $dummyComment = Comment::factory()->make();
        $fillableData =(new Comment())->getFillable();

        $response = $this->json('post', $this->uri, (array)$dummyComment->only($fillableData));
        $response->assertStatus(201);
        Event::assertDispatched(CommentCreated::class);

        $data = $response->json('data');

        $result = collect($data)->only($fillableData);

        collect($result)->each(function($value, $key) use($dummyComment) {
            $this->assertSame(data_get($dummyComment, $key), $value, 'Created comment fillable is not the same');
        });
    }

    public function test_update() {
        Event::fake();
        $dummyComment = Comment::factory()->create();
        $tempComment = Comment::factory()->make();

        $fillables = collect((new Comment())->getFillable());

        $fillables->each(function($toUpdate) use($dummyComment, $tempComment) {
            $response = $this->json('patch',  $this->uri . $dummyComment->id, [
                $toUpdate => data_get($tempComment, $toUpdate)
            ]);

            $response->assertStatus(200);
            Event::assertDispatched(CommentUpdated::class);

            $this->assertSame((int)data_get($dummyComment->refresh(), $toUpdate), (int)data_get($tempComment, $toUpdate), 'Failed to update model');
        });

    }

    public function test_delete() {
        Event::fake();
        // Create a post in db
        $dummyComment = Comment::factory()->create();

        // Request Comment delete
        $response = $this->json('delete', $this->uri . $dummyComment->id);
        $response->assertStatus(200);
        Event::assertDispatched(CommentDeleted::class);

        // Assert Comment doesn't exist
        $this->expectException(ModelNotFoundException::class);
        Comment::query()->findOrFail($dummyComment->id);
    }
}
