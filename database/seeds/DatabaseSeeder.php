<?php

use App\User;
use App\BlogPost;
use App\Comment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $doe = factory(User::class)->states('john-doe')->create();
        $else = factory(User::class, 20)->create();

        $users = $else->concat([$doe]);
        // dd(get_class($doe), get_class($else));
        // dd($users->count());
        $posts = factory(BlogPost::class, 50)->make()->each(function($post) use ($users) {
            $post->user_id = $users->random()->id;
            $post->save();
        });

        $comments = factory(Comment::class, 150)->make()->each(function($comment) use ($posts) {
            $comment->blog_post_id = $posts->random()->id;
            $comment->save();
        });
    }
}
