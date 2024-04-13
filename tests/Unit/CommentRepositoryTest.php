<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use App\Exceptions\GeneralJsonException;

class CommentRepositoryTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create()
    {
        // Goal
        // Replicate env
        $repository = $this->app->make(CommentRepository::class);

        // Source of truth
        $post = \App\Models\Post::factory()->create();
        $user = \App\Models\User::factory()->create();
        $payload = [
            'body' => 'comment body',
            'user_id' => $user->id,
            'post_id' => $post->id
        ];

        // Compare results
        $created = $repository->create($payload);
        $this->assertSame($payload['body'], $created->body, 'Created comment body is not matching');
        $this->assertSame($user->id, $created->user_id, 'Created comment user_id is not matching');
        $this->assertSame($post->id, $created->post_id, 'Created comment post_id is not matching');
    }

    public function test_update() {
        // Replicate env
        $repository = $this->app->make(CommentRepository::class);
        $comment = Comment::factory()->create();

        // Source of truth
        $payload = [
            'body' => ['new comment body'],
        ];

        // Compare results
        $updated = $repository->update($comment, $payload);

        $this->assertSame($payload['body'], $updated->body, 'Updated comment body is not matching');
    }

    public function test_delete() {
        // Replicate env
        $repository = $this->app->make(CommentRepository::class);
        $dummyComment = Comment::factory()->create();

        // Compare results
        $deleted = $repository->forceDelete($dummyComment);
        $found = Comment::query()->find($dummyComment->id);

        $this->assertSame(null, $found, 'Deleted comment is not correct');
    }

    public function test_delete_exception_for_non_existing_user() {
        // Replicate env
        $repository = $this->app->make(CommentRepository::class);
        $dummyComment = Comment::factory(1)->make()->first();

        // Compare results
        $this->expectException(GeneralJsonException::class);
        $deleted = $repository->forceDelete($dummyComment);
    }
}
