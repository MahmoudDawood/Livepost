<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostRepository extends BaseRepository {
    public function create(array $attributes) {
        $post = DB::transaction(function() use($attributes) {
            $post = Post::query()->create([
                'title' => data_get($attributes, 'title', 'Untitled'),
                'body' => data_get($attributes, 'body'),
            ]);

            $post->users()->sync(data_get($attributes, 'user_ids'));
            return $post;
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

        if(!$updated) {
            return new \Exception('Failed to update post');
        }

        return $post;
    }

    /**
     * @param Post $post
     * @return mixed
     */
    public function forceDelete($post) {

        $deleted = $post->forceDelete();
        
        if(!$deleted) {
            return new \Exception('Failed to delete post');
        }

        return $deleted;
    }
}