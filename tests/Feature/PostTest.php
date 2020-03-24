<?php

namespace Tests\Feature;

use Illuminate\Support\Arr;
use App\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function testNoBlogPostWhenNothingInDatabase()
    {
        $response = $this->get('/posts');

        $response->assertSeeText('No blog posts yet!');
    }


    public function testSee1BlogPostWhenThereIsOne()
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


    public function testStoreValid()
    {
        $params = [
            'title' => 'Valid title',
            'content' => 'At least 10 characters',
        ];

        $this->post('/posts', $params)
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

        $this->post('/posts', $params)
            ->assertStatus(302)
            ->assertSessionHas('errors');

        $messages = session('errors')->getMessages();

        $this->assertEquals($messages['title'][0], 'The title must be at least 5 characters.');
        $this->assertEquals($messages['content'][0], 'The content must be at least 10 characters.');
    }


    public function testUpdateValid()
    {
        $post = $this->createDummyBlogPost();

        $this->assertDatabaseHas('blog_posts', $post->toArray());

        $params = [
            'title' => 'Params Title',
            'content' => 'Params Content'
        ];

        $this->put("/posts/{$post->id}", $params)
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
            $post = $this->createDummyBlogPost();

            $this->delete("/posts/{$post->id}")
            ->assertStatus(302)
            ->assertSessionHas('status');
            // dd($post->toArray());
            $this->assertEquals(session('status'), 'Blog post was deleted!');
            $this->assertDatabaseMissing('blog_posts', $post->toArray());

        }

    private function createDummyBlogPost(): BlogPost
    {
        $post = new BlogPost();
        // Not Needed fields for testing
        $post->makeHidden([
            'created_at',
            'updated_at',
        ]);
        $post->title = 'New Title';
        $post->content = 'New Content';
        $post->save();

        return $post;
    }

}
