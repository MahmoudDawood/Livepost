<?php

namespace Tests\Unit;

use App\Exceptions\GeneralJsonException;
use Tests\TestCase;
use App\Models\Post;
use App\Repositories\PostRepository;

class PostRepositoryTest extends TestCase
{
    public function test_create() {
        // Goal
        // Replicate env
        $repository = $this->app->make(PostRepository::class);

        // Source of truth
        $payload = [
            'title' => 'post title',
            'body' => []
        ];

        // Compare results
        $post = $repository->create($payload);
        $this->assertSame($payload['title'], $post->title, 'Created post title is not matching');
    }

    public function test_update() {
        // Replicate env
        $repository = $this->app->make(PostRepository::class);
        $post = Post::factory(1)->create()->first();

        // Source of truth
        $payload = [
            'title' => 'post title',
            'body' => []
        ];

        // Compare results
        $updated = $repository->update($post, $payload);
        $this->assertSame($payload['title'], $updated->title, 'Updated post title is not matching');
    }

    public function test_delete() {
        // Replicate env
        $repository = $this->app->make(PostRepository::class);
        $dummyPost = Post::factory(1)->create()->first();

        // Compare results
        $deleted = $repository->forceDelete($dummyPost);
        $found = Post::query()->find($dummyPost->id);

        $this->assertSame(null, $found, 'Deleted post is not correct');
    }

    public function test_delete_exception_for_non_existing_post() {
        // Replicate env
        $repository = $this->app->make(PostRepository::class);
        $dummyPost = Post::factory(1)->make()->first();

        // Compare results
        $this->expectException(GeneralJsonException::class);
        $deleted = $repository->forceDelete($dummyPost);
    }
}
