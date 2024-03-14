<?php

namespace App\Repositories;

use App\Exceptions\GeneralJsonException;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostRepository extends BaseRepository {
    public function create(array $attributes) {
        $post = DB::transaction(function() use($attributes) {
            $newPost = Post::query()->create([
                'title' => data_get($attributes, 'title', 'Untitled'),
                'body' => data_get($attributes, 'body'),
            ]);

            throw_if(!$newPost, new GeneralJsonException('Failed to create post'));

            $userIds = data_get($attributes, 'user_ids');
            if($userIds) {
                $newPost->users()->sync($userIds);
            }

            return $newPost;
        });

        return $post;
    }

    /**
     * @param Post $post
     * @param array $attributes
     * @return mixed
    */
    public function update($post, array $attributes) {
        $updated = $post->update([
            'title' => data_get($attributes, 'title') ?? $post->title,
            'body' => data_get($attributes, 'body') ?? $post->body,
        ]);

        throw_if(!$updated, new GeneralJsonException('Failed to update post'));

        return $post;
    }

    /**
     * @param Post $post
     * @return mixed
     */
    public function forceDelete($post) {
        $deleted = $post->forceDelete();
        
        throw_if(!$deleted, new GeneralJsonException('Failed to delete post'));
    }
}