<?php

namespace Tests\Unit;

use App\Comment;
use Tests\TestDataSetup;

class CommentsTest extends TestDataSetup
{
    /**
     * Total Test Cases: 12
     */

    /**
     * Positive Test Cases: 8
     */

    public function test_index_returns_expected_structure()
    {
        $this->get('/api/comments')
                ->assertStatus(200)
                ->assertJsonStructure([ 
                    'length',
                    'data' => [
                        '*' => [
                            'id',
                            'parent_id',
                            'user_id',
                            'commentable_id',
                            'commentable_type',
                            'body',
                            'vote',
                            'offensive_index',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]);
    }

    public function test_index_returns_expected_length()
    {
        $response = $this->get('/api/comments')->decodeResponseJson();
        $this->assertEquals($response['length'], count(Comment::all()));
    }

    public function test_show_returns_expected_structure()
    {
        $this->get('/api/comments/' . $this->comment1->id)
                ->assertStatus(200)
                ->assertJsonStructure([
                    'id',
                    'parent_id',
                    'user_id',
                    'commentable_id',
                    'commentable_type',
                    'body',
                    'vote',
                    'offensive_index',
                    'created_at',
                    'updated_at'
                ]);
    }

    public function test_store_can_persist_page_data()
    {
        $comment = [
            'body' => 'test page comment',
            'commentable_id' => $this->page1->id,
            'commentable_type' => 'App\Page'
        ];

        $this->actingAs($this->author1)
                ->post('/api/comments', $comment)
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

        $this->actingAs($this->author1)
                ->put('/api/comments/' . $this->comment1->id, $comment)
                ->assertStatus(200)
                ->assertJsonFragment($comment);;
    }

    public function test_store_can_persist_category_data()
    {
        $comment = [
            'body' => 'test category comment',
            'commentable_id' => $this->category1->id,
            'commentable_type' => 'App\Category'
        ];

        $this->actingAs($this->author1)
                ->post('/api/comments', $comment)
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

        $this->actingAs($this->author1)
                ->put('/api/comments/' . $this->comment2->id, $comment)
                ->assertStatus(200)
                ->assertJsonFragment($comment);;
    }

    public function test_destroy_can_delete_data()
    {
        $this->delete('/api/comments/1')
                ->assertStatus(200)
                ->assertJsonFragment([$this->comment1->id]);
    }

    
    /**
     * Negative Test Cases: 4
     */

    public function test_show_error_invalid_id()
    {
        $this->get('/api/comments/108')
                ->assertStatus(404);
    }

    public function test_store_error_invalid_data()
    {
        $comment = [
            'commentable_id' => $this->page1->id,
            'commentable_type' => 'App\Page'
        ];

        $this->actingAs($this->author1)
                ->post('/api/comments', $comment)
                ->assertStatus(302);
                //->assertJsonFragment(['message' => 'The given data was invalid.']);
    }

    public function test_update_error_invalid_data()
    {
        $comment = [
            'commentable_id' => $this->page1->id,
            'commentable_type' => 'App\Page'
        ];
        
        $this->actingAs($this->author1)
                ->put('/api/comments/' . $this->comment1->id, $comment)
                ->assertStatus(302);
    }

    public function test_destroy_error_invalid_id()
    {
        $this->delete('/api/comments/108')
                ->assertStatus(404);
    }
}
