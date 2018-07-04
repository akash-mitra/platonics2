<?php

namespace Tests\Unit;

use App\Comment;
use Tests\TestDataSetup;

class CommentsTest extends TestDataSetup
{
    /**
     * Total Test Cases: 8
     */

    /**
     * Positive Test Cases: 5
     */

    public function test_store_can_persist_page_data()
    {
        $comment = [
            'body' => 'test page comment',
            'commentable_id' => $this->page1->id,
            'commentable_type' => 'App\Page'
        ];

        $this->actingAs($this->admin1)
                ->post('comments', $comment)
                ->assertStatus(201)
                ->assertJsonFragment($comment);
    }

    public function test_update_can_persist_page_data()
    {
        $comment = [
            'body' => 'test page comment update',
            'commentable_id' => $this->page1->id,
            'commentable_type' => 'App\Page'
        ];

        $this->actingAs($this->admin1)
                ->patch('comments/' . $this->comment1->id, $comment)
                ->assertStatus(200)
                ->assertJsonFragment($comment);
    }

    public function test_store_can_persist_category_data()
    {
        $comment = [
            'body' => 'test category comment',
            'commentable_id' => $this->category1->id,
            'commentable_type' => 'App\Category'
        ];

        $this->actingAs($this->admin1)
                ->post('comments', $comment)
                ->assertStatus(201)
                ->assertJsonFragment($comment);
    }

    public function test_update_can_persist_category_data()
    {
        $comment = [
            'body' => 'test category comment update',
            'commentable_id' => $this->category2->id,
            'commentable_type' => 'App\Category'
        ];

        $this->actingAs($this->admin1)
                ->patch('comments/' . $this->comment2->id, $comment)
                ->assertStatus(200)
                ->assertJsonFragment($comment);;
    }

    public function test_destroy_can_delete_data()
    {
        $this->actingAs($this->admin1)
                ->delete('admin/comments/1')
                ->assertStatus(200)
                ->assertJsonFragment([$this->comment1->id]);
    }
    
    
    /**
     * Negative Test Cases: 3
     */

    public function test_store_error_invalid_data()
    {
        $comment = [
            'commentable_id' => $this->page1->id,
            'commentable_type' => 'App\Page'
        ];

        $this->actingAs($this->admin1)
                ->post('comments', $comment)
                ->assertStatus(302);
                //->assertJsonFragment(['message' => 'The given data was invalid.']);
    }

    public function test_update_error_invalid_data()
    {
        $comment = [
            'commentable_id' => $this->page1->id,
            'commentable_type' => 'App\Page'
        ];
        
        $this->actingAs($this->admin1)
                ->patch('comments/' . $this->comment1->id, $comment)
                ->assertStatus(302);
    }

    public function test_destroy_error_invalid_id()
    {
        $this->actingAs($this->admin1)
                ->delete('admin/comments/108')
                ->assertStatus(404);
    }
}