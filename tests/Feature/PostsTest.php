<?php

namespace Tests\Feature;


use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Session;

class PostsTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */

    /** @test  */
    public function test_a_user_can_browse_posts()
    {
        $response = $this->get('/posts');
        $response->assertStatus(200);
    }

    public function test_a_user_can_receive_posts()
    {
        $post = Post::factory()->create();
        $response = $this->get('/posts');
        $response->assertSee($post->title);
    }

    public function test_existing_post_can_be_deleted()
    {
        $post = Post::factory()->create();
        $this->delete('posts/' . $post->id)
        ->assertStatus(200);    
    }

    public function test_non_existing_post_can_not_deleted()
    {
        $post = Post::factory()->create();
        $this->delete('posts/' . 0000)
        ->assertStatus(404);    
    }
    
    public function test_existing_post_can_be_Edit()
    {
        $post = Post::factory()->create();
        $this->get('posts/'.$post->id.'/edit')
        ->assertStatus(200);
    } 

    public function test_non_existing_post_can_not_be_Edit()
    {
        $post = Post::factory()->create();
        $id = 0000;
        $this->get('posts/'.$id.'/edit')
        ->assertStatus(404);
    } 

    public function test_user_can_browse_create_post()
    {  
        $response = $this->get('posts/create');
        $response->assertStatus(200);
    }

    public function test_user_can_store_create_post()
    {  
        $response = $this->post('posts',[
            'title' => 'abc',
            'description' => 'xyz'
        ]);
        $response->assertSessionHasNoErrors();
    }

    public function test_validations_are_working_in_create_post()
    {
        $response = $this->post('posts',[
            'title' => '',
            'description' => ''
        ]);
        $response->assertSessionHasErrors();
    }

    public function test_validations_are_working_in_update_post()
    {
        $post = Post::factory()->create();
        $response = $this->call('PUT','posts/'.$post->id,[
            'title' => '',
            'description' => ''
        ]);
        $response->assertSessionHasErrors();
    }

    public function test_user_able_to_update_post()
    {
        $post = Post::factory()->create();
        $response = $this->call('PUT','posts/'.$post->id,[
            'title' => 'abc',
            'description' => 'xyz'
        ]);
        $response->assertSessionHasNoErrors();
    }

    public function test_user_not_able_to_update_post_with_wrong_id()
    {
        $id = 0000;
        $response = $this->call('PUT','posts/'.$id,[
            'title' => 'abc',
            'description' => 'xyz'
        ]);
        $response->assertStatus(404);
    }
}
