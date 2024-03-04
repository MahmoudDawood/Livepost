<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Database\Factories\CommentFactory;
use Database\Seeders\Traits\TruncateTable;
use Database\Factories\Helpers\FactoryHelper;
use Database\Seeders\Traits\DisableForeignKeys;

class PostSeeder extends Seeder
{
    use TruncateTable, DisableForeignKeys;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeyChecks();
        $this->truncate('posts');
        $posts = Post::factory(3)
            // ->has(Comment::factory(3), 'comments')
            ->untitled()
            ->create();

        // Apply many to many relation to users
        $posts->each(function (Post $post) {
            $post->users()->sync(FactoryHelper::getRandomModelId(User::class));
        });

        $this->enableForeignKeyChecks();
    }
}
