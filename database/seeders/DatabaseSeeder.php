<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Post;
use App\Models\User;
use App\Models\Topic;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(TopicSeeder::class);
        $topics = Topic::all();

        $users = User::factory(10)->create();

        $posts = Post::factory(200)
            ->withFixtures()
            ->has(Comment::factory(15)->recycle($users))
            ->recycle([$users, $topics])
            ->create();


        $najibu = User::factory()
            ->has(Post::factory(45)->recycle($topics)->withFixtures())
            ->has(Comment::factory(120)->recycle($posts))
            ->create([
                'name' => 'Najibu Nsubuga',
                'email' => 'najibu@test.com',
                'password' => bcrypt('Password')
            ]);
    }
}
