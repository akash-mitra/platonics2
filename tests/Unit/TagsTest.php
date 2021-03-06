<?php

namespace Tests\Unit;

use App\Tag;
use Tests\TestDataSetup;

class TagsTest extends TestDataSetup
{
    /**
     * Total Test Cases: 19
     */

    /**
     * Positive Test Cases: 15
     */
    
    public function test_index_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('api/tags')
                ->assertStatus(200)
                ->assertJsonStructure([ 
                    'length',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'description',
                            'created_at',
                            'updated_at',
                            'user' => [
                                'name',
                                'type',
                                'slug',
                                'avatar',
                                'created_at',
                                'updated_at',
                            ]
                        ]
                    ]
                ]);
    }

    public function test_index_returns_expected_length()
    {
        $response = $this->actingAs($this->admin1)->get('api/tags')->decodeResponseJson();
        $this->assertEquals($response['length'], count(Tag::all()));
    }

    public function test_show_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('api/tags/' . $this->tag1->id)
                ->assertStatus(200)
                ->assertJsonStructure([
                    'id',
                    'name',
                    'description',
                    'created_at',
                    'updated_at',
                    'user' => [
                        'name',
                        'type',
                        'slug',
                        'avatar',
                        'created_at',
                        'updated_at',
                    ]
                ]);
    }

    public function test_store_can_persist_data()
    {
        $tag = [
            'name' => 'test tag',
            'description' => 'test tag desc'
        ];

        $this->actingAs($this->admin1)
                ->post('admin/tags', $tag)
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
                ->patch('admin/tags/' . $this->tag1->id, $tag)
                ->assertStatus(200)
                ->assertJsonFragment($tag);
    }

    public function test_destroy_can_delete_data()
    {
        $this->actingAs($this->admin1)
                ->delete('admin/tags/1')
                ->assertStatus(200)
                ->assertJsonFragment([$this->tag1->name]);
    }

    public function test_attach_can_add_tags_to_category()
    {
        $tag = [
            'tags' => [ $this->tag3->id ],
            'taggable_id' => $this->category3->id,
            'taggable_type' => 'App\Category'
        ];

        $this->actingAs($this->admin1)
                ->post('api/tags/attach', $tag)
                ->assertStatus(200)
                ->assertJsonFragment(['status' => 'success']);
    }

    public function test_detach_can_remove_tags_from_category()
    {
        $tag = [
            'tag_id' => $this->tag2->id,
            'taggable_id' => $this->category2->id,
            'taggable_type' => 'App\Category'
        ];

        $this->actingAs($this->admin1)
                ->post('api/tags/detach', $tag)
                ->assertStatus(200)
                ->assertJsonFragment(['status' => 'success']);
    }

    public function test_attach_can_add_tags_to_page()
    {
        $tag = [
            'tags' => [ $this->tag3->id ],
            'taggable_id' => $this->page3->id,
            'taggable_type' => 'App\Page'
        ];

        $this->actingAs($this->admin1)
                ->post('api/tags/attach', $tag)
                ->assertStatus(200)
                ->assertJsonFragment(['status' => 'success']);
    }

    public function test_detach_can_remove_tags_from_page()
    {
        $tag = [
            'tag_id' => $this->tag2->id,
            'taggable_id' => $this->page2->id,
            'taggable_type' => 'App\Page'
        ];

        $this->actingAs($this->admin1)
                ->post('api/tags/detach', $tag)
                ->assertStatus(200)
                ->assertJsonFragment(['status' => 'success']);
    }

    public function test_fullattach_can_add_tags_to_category()
    {
        $tag = [
            'tags' => [ $this->tag2->id, $this->tag3->id ],
            'taggable_id' => $this->category3->id,
            'taggable_type' => 'App\Category'
        ];

        $this->actingAs($this->admin1)
                ->post('api/tags/fullattach', $tag)
                ->assertStatus(200)
                ->assertJsonFragment(['status' => 'success']);
    }

    public function test_fullattach_can_add_tags_to_page()
    {
        $tag = [
            'tags' => [ $this->tag2->id, $this->tag3->id ],
            'taggable_id' => $this->page3->id,
            'taggable_type' => 'App\Page'
        ];

        $this->actingAs($this->admin1)
                ->post('api/tags/fullattach', $tag)
                ->assertStatus(200)
                ->assertJsonFragment(['status' => 'success']);
    }

    public function test_categories_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('api/tags/' . $this->tag1->name . '/categories')
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
                ->get('api/tags/' . $this->tag1->name . '/pages')
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

    public function test_all_taggables_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('api/tags/' . $this->tag1->name . '/all')
                ->assertStatus(200)
                ->assertJsonStructure([
                    'length',
                    'data' => [
                        'categories' => [
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
                        ],
                        'pages' => [
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
                ->get('api/tags/108')
                ->assertStatus(404);
    }

    public function test_store_error_invalid_data()
    {
        $tag = [
            'description' => 'test tag desc'
        ];

        $this->actingAs($this->admin1)
                ->post('admin/tags', $tag)
                ->assertStatus(302);
                //->assertJsonFragment(['message' => 'The given data was invalid.']);
    }

    public function test_update_error_invalid_data()
    {
        $tag = [
            'description' => 'test tag desc update'
        ];
        
        $this->actingAs($this->admin1)
                ->patch('admin/tags/' . $this->tag1->id, $tag)
                ->assertStatus(302);
    }

    public function test_destroy_error_invalid_id()
    {
        $this->actingAs($this->admin1)
                ->delete('admin/tags/108')
                ->assertStatus(404);
    }
}