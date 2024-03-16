<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Exceptions\GeneralJsonException;
use App\Events\Models\Comment\CommentCreated;
use App\Events\Models\Comment\CommentDeleted;
use App\Events\Models\Comment\CommentUpdated;

class CommentRepository extends BaseRepository {
    public function create(array $attributes) {
        $comment = Comment::query()->create([
            'body' => data_get($attributes, 'body'),
            'user_id' => data_get($attributes, 'user_id'),
            'post_id' => data_get($attributes, 'post_id'),
        ]);

        throw_if(!$comment, new GeneralJsonException('Failed to create comment'));
        event(new CommentCreated($comment));

        return $comment;
    }

    public function update($comment, array $attributes) {
        $updated = $comment->update([
            'body' => data_get($attributes, 'body') ?? $comment->body,
        ]);

        throw_if(!$updated, new GeneralJsonException('Failed to update comment'));
        event(new CommentUpdated($comment));

        return $comment;
    }

    public function forceDelete($comment) {
        $deleted = $comment->forceDelete();

        throw_if(!$deleted, new GeneralJsonException('Failed to delete comment'));
        event(new CommentDeleted($comment));
    }
}