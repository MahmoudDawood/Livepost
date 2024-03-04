<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\TruncateTable;
use Database\Seeders\Traits\DisableForeignKeys;

class CommentSeeder extends Seeder
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
        $this->truncate('comments');
        Comment::factory(6)
            // ->for(Post::factory(1), 'post')
            ->create();
        $this->enableForeignKeyChecks();
    }
}
