<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\BlogPost;
use App\Comment;


class PostTest extends TestCase
{
    use RefreshDatabase;

    public function testNoBlogPostWhenNothingInDatabase()
    {
        $response = $this->get('/posts');

        $response->assertSeeText('No blog posts yet!');
    }


    public function testSee1BlogPostWhenThereIs1WithNoComments()
    {
        // Arrange part of the Test
        $post = $this->createDummyBlogPost();

        // Act
        $response = $this->get('/posts');

        // Assert
        $response->assertSeeText('New Title');
        $response->assertSeeText('New Content');
        $response->assertSeeText('No Comments yet!');

        $this->assertDatabaseHas('blog_posts', [
            'title' => 'New Title',
            'content' => 'New Content',
        ]);
    }

    public function testSee1BlogPostWithComments()
    {
        // Arrange part of the Test
        $post = $this->createDummyBlogPost();
        factory(Comment::class, 4)->create([
            'blog_post_id' => $post->id
        ]);

        // Act
        $response = $this->get('/posts');

        // Assert
        $response->assertSeeText('4 comments');

    }



    public function testStoreValid()
    {
        $params = [
            'title' => 'Valid title',
            'content' => 'At least 10 characters',
        ];

        $this->actingAs($this->user())
            ->post('/posts', $params)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog post was created!');
    }


    public function testStoreFail()
    {
        $params = [
            'title' => 'x',
            'content' => 'y',
        ];

        $this->actingAs($this->user())
            ->post('/posts', $params)
            ->assertStatus(302)
            ->assertSessionHas('errors');

        $messages = session('errors')->getMessages();

        $this->assertEquals($messages['title'][0], 'The title must be at least 5 characters.');
        $this->assertEquals($messages['content'][0], 'The content must be at least 10 characters.');
    }


    public function testUpdateValid()
    {
        $user = $this->user();
        $post = $this->createDummyBlogPost($user->id);

        // $this->assertDatabaseHas('blog_posts', $post->toArray());

        $params = [
            'title' => 'Params Title',
            'content' => 'Params Content'
        ];

        $this->actingAs($user)
            ->put("/posts/{$post->id}", $params)
            ->assertStatus(302)
            ->assertSessionHas('status');
        // dd($post->toArray());

        $this->assertEquals(session('status'), 'Blog post was updated!');
        $this->assertDatabaseMissing('blog_posts', $post->toArray());
            $this->assertDatabaseHas('blog_posts', [
            'title' => 'Params Title'
            ]);
    }


    public function testDelete()
    {
        $user = $this->user();
        $post = $this->createDummyBlogPost($user->id);
        $this->assertDatabaseHas('blog_posts', $post->toArray());

        $this->actingAs($user)
            ->delete("/posts/{$post->id}")
            ->assertStatus(302)
            ->assertSessionHas('status');
        // dd($post->toArray());
        $this->assertEquals(session('status'), 'Blog post was deleted!');
        // $this->assertDatabaseMissing('blog_posts', $post->toArray());
        $this->assertSoftDeleted('blog_posts', $post->toArray());

    }

    private function createDummyBlogPost($userId = null): BlogPost
    {
//        $post = new BlogPost();

        $post = factory(BlogPost::class)->state('new-title')->create(
            [
                'user_id' => $userId ?? $this->user()->id
            ]
        );


//        // Not Needed fields for testing
        $post->makeHidden([
            'created_at',
            'updated_at',
        ]);
//        $post->title = 'New Title';
//        $post->content = 'New Content';
//        $post->save();

        return $post;
    }

}
