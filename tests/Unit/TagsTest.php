<?php

namespace Tests\Unit;

use App\Tag;
use Tests\TestDataSetup;

class TagsTest extends TestDataSetup
{
    /**
     * Total Test Cases: 14
     */

    /**
     * Positive Test Cases: 12
     */

    public function test_index_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('/tags')
                ->assertStatus(200)
                ->assertJsonStructure([ 
                    'length',
                    'data' => [
                        '*' => [
                            'id',
                            'user_id',
                            'name',
                            'description',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]);
    }

    public function test_index_returns_expected_length()
    {
        $response = $this->actingAs($this->admin1)->get('/tags')->decodeResponseJson();
        $this->assertEquals($response['length'], count(Tag::all()));
    }

    public function test_show_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('/tags/' . $this->tag1->id)
                ->assertStatus(200)
                ->assertJsonStructure([
                    'id',
                    'user_id',
                    'name',
                    'description',
                    'created_at',
                    'updated_at'
                ]);
    }

    public function test_store_can_persist_data()
    {
        $tag = [
            'name' => 'test tag',
            'description' => 'test tag desc'
        ];

        $this->actingAs($this->admin1)
                ->post('/tags', $tag)
                ->assertStatus(201)
                ->assertJsonFragment($tag);
    }

    public function test_update_can_persist_data()
    {
        $tag = [
            'name' => 'test tag update',
            'description' => 'test tag desc update'
        ];

        $this->actingAs($this->admin1)
                ->put('/tags/' . $this->tag1->id, $tag)
                ->assertStatus(200)
                ->assertJsonFragment($tag);
    }

    public function test_destroy_can_delete_data()
    {
        $this->actingAs($this->admin1)
                ->delete('/tags/1')
                ->assertStatus(200)
                ->assertJsonFragment([$this->tag1->name]);
    }

    public function test_attach_can_add_tags_to_category()
    {
        $tag = [
            'tag_id' => $this->tag3->id,
            'taggable_id' => $this->category3->id,
            'taggable_type' => 'App\Category'
        ];

        $this->actingAs($this->admin1)
                ->post('/tags/attach', $tag)
                ->assertStatus(201)
                ->assertJsonFragment(['id' => $this->tag3->id]);
    }

    public function test_detach_can_remove_tags_from_category()
    {
        $tag = [
            'tag_id' => $this->tag2->id,
            'taggable_id' => $this->category2->id,
            'taggable_type' => 'App\Category'
        ];

        $this->actingAs($this->admin1)
                ->post('/tags/detach', $tag)
                ->assertStatus(200)
                ->assertJsonFragment(['id' => $this->tag2->id]);
    }

    public function test_attach_can_add_tags_to_page()
    {
        $tag = [
            'tag_id' => $this->tag3->id,
            'taggable_id' => $this->page3->id,
            'taggable_type' => 'App\Page'
        ];

        $this->actingAs($this->admin1)
                ->post('/tags/attach', $tag)
                ->assertStatus(201)
                ->assertJsonFragment(['id' => $this->tag3->id]);
    }

    public function test_detach_can_remove_tags_from_page()
    {
        $tag = [
            'tag_id' => $this->tag2->id,
            'taggable_id' => $this->page2->id,
            'taggable_type' => 'App\Page'
        ];

        $this->actingAs($this->admin1)
                ->post('/tags/detach', $tag)
                ->assertStatus(200)
                ->assertJsonFragment(['id' => $this->tag2->id]);
    }

    public function test_categories_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('/tags/' . $this->tag1->id . '/categories')
                ->assertStatus(200)
                ->assertJsonStructure([
                    'length',
                    'data' => [
                        '*' => [
                            'id',
                            'parent_id',
                            'name',
                            'description',
                            'access_level',
                            'created_at', 
                            'updated_at',
                            'pivot' => [
                                'tag_id',
                                'taggable_id',
                                'taggable_type'
                            ]
                        ]
                    ]
                ]);
    }

    public function test_pages_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('/tags/' . $this->tag1->id . '/pages')
                ->assertStatus(200)
                ->assertJsonStructure([
                    'length',
                    'data' => [
                        '*' => [
                            'id',
                            'category_id',
                            'user_id',
                            'title',
                            'summary',
                            'metakey',
                            'metadesc',
                            'media_url',
                            'access_level',
                            'publish',
                            'created_at', 
                            'updated_at',
                            'pivot' => [
                                'tag_id',
                                'taggable_id',
                                'taggable_type'
                            ]
                        ]
                    ]
                ]);
    }
    
    /**
     * Negative Test Cases: 4
     */

    public function test_show_error_invalid_id()
    {
        $this->actingAs($this->admin1)
                ->get('/tags/108')
                ->assertStatus(404);
    }

    public function test_store_error_invalid_data()
    {
        $tag = [
            'description' => 'test tag desc'
        ];

        $this->actingAs($this->admin1)
                ->post('/tags', $tag)
                ->assertStatus(302);
                //->assertJsonFragment(['message' => 'The given data was invalid.']);
    }

    public function test_update_error_invalid_data()
    {
        $tag = [
            'description' => 'test tag desc update'
        ];
        
        $this->actingAs($this->admin1)
                ->put('/tags/' . $this->tag1->id, $tag)
                ->assertStatus(302);
    }

    public function test_destroy_error_invalid_id()
    {
        $this->actingAs($this->admin1)
                ->delete('/tags/108')
                ->assertStatus(404);
    }
}
