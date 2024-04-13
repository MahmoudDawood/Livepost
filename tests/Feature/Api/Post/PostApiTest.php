<?php

namespace Tests\Feature\Api\Post;

use Nette\Utils\AssertionException;
use Tests\TestCase;
use App\Models\Post;
use Illuminate\Support\Facades\Event;
use App\Events\Models\Post\PostCreated;

use App\Events\Models\Post\PostDeleted;
use App\Events\Models\Post\PostUpdated;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use function PHPUnit\Framework\assertSame;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    protected $uri = '/api/v1/posts/';

    public function test_index() {
        $posts = Post::factory(10)->create();
        $postIds = $posts->map(function($post) {
            return $post->id;
        });

        $response = $this->json('get', $this->uri);
        
        $data = $response->assertStatus(200);
        $data = $response->json('data');

        collect($data)->each(function($post) use($postIds) {
            $this->assertTrue(in_array($post['id'], $postIds->toArray()));
        });
    }

    public function test_show() {
        $dummyPost = Post::factory()->create();

        $response = $this->json('get',  $this->uri . $dummyPost->id);
        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertSame(data_get($data, 'id'), $dummyPost->id, 'Response ID does not match model id.');
    }

    public function test_create() {
        Event::fake();
        $dummyPost = Post::factory()->make();

        $response = $this->json('post', $this->uri, $dummyPost->toArray());
        $response->assertStatus(201);
        Event::assertDispatched(PostCreated::class);
        
        $data = $response->json('data');
        $fillable = $dummyPost->getFillable();

        $result = collect($data)->only($fillable);

        collect($result)->each(function($value, $key) use($dummyPost) {
            $this->assertSame(data_get($dummyPost, $key), $value, 'Created post fillable is not the same');
        });
    }

    public function test_update() {
        Event::fake();
        $dummyPost = Post::factory()->create();
        $tempPost = Post::factory()->make();

        $fillables = collect((new Post())->getFillable());

        $fillables->each(function($toUpdate) use($dummyPost, $tempPost) {
            $response = $this->json('patch',  $this->uri . $dummyPost->id, [
                $toUpdate => data_get($tempPost, $toUpdate)
            ]);

            $response->assertStatus(200);
            Event::assertDispatched(PostUpdated::class);

            $this->assertSame(data_get($dummyPost->refresh(), $toUpdate), data_get($tempPost, $toUpdate), 'Failed to update model');
        });

    }
    
    public function test_delete() {
        Event::fake();
        // Create a post in db
        $dummyPost = Post::factory()->create();

        // Request post delete
        $response = $this->json('delete', $this->uri . $dummyPost->id);
        $response->assertStatus(200);
        Event::assertDispatched(PostDeleted::class);

        // Assert post doesn't exist
        $this->expectException(ModelNotFoundException::class);
        Post::query()->findOrFail($dummyPost->id);
    }
}
