<?php

namespace App\Repositories;

use App\Models\Comment;

class CommentRepository extends BaseRepository {
    public function create(array $attributes) {
        $comment = Comment::query()->create([
            'body' => data_get($attributes, 'body'),
            'user_id' => data_get($attributes, 'user_id'),
            'post_id' => data_get($attributes, 'post_id'),
        ]);

        return $comment;
    }

    public function update($comment, array $attributes) {
        $updated = $comment->update([
            'body' => data_get($attributes, 'body') ?? $comment->body,
        ]);

        if(!$updated) {
            throw new \Exception('Failed to update comment');
        }

        return $comment;
    }

    public function forceDelete($comment) {
        $deleted = $comment->forceDelete();

        if(!$deleted) {
            throw new \Exception('Failed to delete comment');
        }

        return $comment;
    }
}