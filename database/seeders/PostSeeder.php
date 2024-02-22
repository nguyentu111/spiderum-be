<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $post = Post::create([
            'name' => 'name',
            'content' => json_encode(['haha' => '123']),
            'slug' => 'haha',
            'author_id' => '627f3002-ab34-4e01-a51b-10dda3cefcf4',
        ]);

        $post->comments()->saveMany([
            new Comment([
                'id' => 'f5507cfc-b53b-4660-b64f-117466314287',
                'content' => 'Bài viết hay quá',
                'user_id' => 'cc937bdd-0bcb-4583-bd69-31b4d3bdf413',
                'post_id' => $post->getKey(),
            ]),
            new Comment([
                'id' => '0ed60184-d7bb-4e9e-af98-b4b2a7b0a40c',
                'content' => 'Haha',
                'user_id' => '2c617ce9-1bad-4a7f-b240-1fdd89e1aeb3',
                'post_id' => $post->getKey(),
            ])
        ]);

        Comment::create([
            'id' => 'a96ffff3-df0a-4f0c-bd55-df301af3abee',
            'content' => 'hihi',
            'user_id' => '627f3002-ab34-4e01-a51b-10dda3cefcf4',
            'post_id' => $post->getKey(),
            'parent_id' => '0ed60184-d7bb-4e9e-af98-b4b2a7b0a40c'
        ]);

        Comment::create([
            'content' => '123123123123',
            'user_id' => '2c617ce9-1bad-4a7f-b240-1fdd89e1aeb3',
            'post_id' => $post->getKey(),
            'parent_id' => 'a96ffff3-df0a-4f0c-bd55-df301af3abee'
        ]);
    }
}
