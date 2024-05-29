<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::factory()->hasComments(rand(12, 30))
            ->count(20)
            ->create(['type' => 'post']);

        Post::factory()->hasComments(rand(12, 30))
            ->count(12)
            ->create(['type' => 'reel']);

        Comment::limit(50)->each(function (Comment $comment) {
            $comment::factory(rand(1, 5))
                ->isReply($comment->commentable)
                ->create(['parent_id' => $comment->id]);
        });
    }
}
