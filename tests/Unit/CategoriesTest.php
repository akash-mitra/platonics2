<?php

namespace Tests\Unit;

use App\Category;
use Tests\TestDataSetup;

class CategoriesTest extends TestDataSetup
{
    /**
     * Total Test Cases: 45
     */

    /**
     * Positive Test Cases: 9
     */

    public function test_index_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('api/categories')
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
                            'updated_at'
                        ]
                    ]
                ]);
    }

    public function test_index_returns_expected_length()
    {
        $response = $this->actingAs($this->admin1)->get('api/categories')->decodeResponseJson();
        $this->assertEquals($response['length'], count(Category::all()));
    }

    public function test_show_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('api/categories/' . $this->category1->id)
                ->assertStatus(200)
                ->assertJsonStructure([
                    'id',
                    'parent_id',
                    'name',
                    'description',
                    'access_level',
                    'created_at',
                    'updated_at'
                ]);
    }

    public function test_store_can_persist_data()
    {
        $category = [
            'name' => 'test category',
            'description' => 'test category desc',
            'access_level' => 'F'
        ];

        $this->actingAs($this->admin1)
                ->post('admin/categories', $category)
                ->assertStatus(201)
                ->assertJsonFragment($category);
    }

    public function test_update_can_persist_data()
    {
        $category = [
            'name' => 'test category update',
            'description' => 'test category desc update',
            'access_level' => 'F'
        ];

        $this->actingAs($this->admin1)
                ->put('admin/categories/' . $this->category1->id, $category)
                ->assertStatus(200)
                ->assertJsonFragment($category);
    }

    public function test_destroy_can_delete_data()
    {
        $this->actingAs($this->admin1)
                ->delete('admin/categories/1')
                ->assertStatus(200)
                ->assertJsonFragment([$this->category1->name]);
    }

    public function test_tags_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('api/tags/categories/2')
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
                            ],
                            'pivot' => [
                                'taggable_id',
                                'tag_id',
                                'taggable_type'
                            ]
                        ]
                    ]
                ]);
    }

    public function test_comments_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('api/comments/categories/2')
                ->assertStatus(200)
                ->assertJsonStructure([ 
                    'length',
                    'data' => [
                        '*' => [
                            'id',
                            'parent_id',
                            'commentable_id',
                            'commentable_type',
                            'body',
                            'vote',
                            'offensive_index',
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

    public function test_pages_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('api/categories/1/pages')
                ->assertStatus(200)
                ->assertJsonStructure([ 
                    'length',
                    'data' => [
                        '*' => [
                            'id',
                            'category_id',
                            'title',
                            'summary',
                            'metakey',
                            'metadesc',
                            'media_url',
                            'access_level',
                            'publish',
                            'draft',
                            'created_at',
                            'updated_at',
                            'users' => [
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


    /**
     * Negative Test Cases: 4
     */

    public function test_show_error_invalid_id()
    {
        $this->actingAs($this->admin1)
                ->get('api/categories/108')
                ->assertStatus(404);
    }

    public function test_store_error_invalid_data()
    {
        $category = [
            'name' => 'test category',
            'description' => 'test category desc',
            'access_level' => 'X'
        ];

        $this->actingAs($this->admin1)
                ->post('admin/categories', $category)
                ->assertStatus(302);
                //->assertJsonFragment(['message' => 'The given data was invalid.']);
    }

    public function test_update_error_invalid_data()
    {
        $category = [
            'name' => 'test category update',
            'description' => 'test category desc update',
            'access_level' => 'X'
        ];
        
        $this->actingAs($this->admin1)
                ->put('admin/categories/' . $this->category1->id, $category)
                ->assertStatus(302);
    }

    public function test_destroy_error_invalid_id()
    {
        $this->actingAs($this->admin1)
                ->delete('admin/categories/108')
                ->assertStatus(404);
    }


    /**
     * User Type Permissions Cases: 8 * 4 = 32
     */

    public function test_visitor_allow_index()
    {
        $this->get('api/categories')
                ->assertStatus(200);
    }

    public function test_visitor_allow_show()
    {
        $this->get('api/categories/' . $this->category1->id)
                ->assertStatus(200);
    }

    public function test_visitor_allow_tags()
    {
        $this->get('api/tags/categories/2')
                ->assertStatus(200);
    }

    public function test_visitor_allow_comments()
    {
        $this->get('api/comments/categories/2')
                ->assertStatus(200);
    }

    public function test_visitor_allow_pages()
    {
        $this->get('api/categories/1/pages')
                ->assertStatus(200);
    }

    public function test_visitor_deny_store()
    {
        $category = [
            'name' => 'test category',
            'description' => 'test category desc',
            'access_level' => 'F'
        ];

        $this->post('admin/categories', $category)
                ->assertStatus(302);
    }

    public function test_visitor_deny_update()
    {
        $category = [
            'name' => 'test category update',
            'description' => 'test category desc update',
            'access_level' => 'F'
        ];

        $this->put('admin/categories/' . $this->category1->id, $category)
                ->assertStatus(302);
    }

    public function test_visitor_deny_destroy()
    {
        $this->delete('admin/categories/1')
                ->assertStatus(302);
    }


    public function test_regular_allow_index()
    {
        $this->actingAs($this->regular1)
                ->get('api/categories')
                ->assertStatus(200);
    }

    public function test_regular_allow_show()
    {
        $this->actingAs($this->regular1)
                ->get('api/categories/' . $this->category1->id)
                ->assertStatus(200);
    }

    public function test_regular_allow_tags()
    {
        $this->actingAs($this->regular1)
                ->get('api/tags/categories/2')
                ->assertStatus(200);
    }

    public function test_regular_allow_comments()
    {
        $this->actingAs($this->regular1)
                ->get('api/comments/categories/2')
                ->assertStatus(200);
    }

    public function test_regular_allow_pages()
    {
        $this->actingAs($this->regular1)
                ->get('api/categories/1/pages')
                ->assertStatus(200);
    }

    public function test_regular_deny_store()
    {
        $category = [
            'name' => 'test category',
            'description' => 'test category desc',
            'access_level' => 'F'
        ];

        $this->actingAs($this->regular1)
                ->post('admin/categories', $category)
                ->assertStatus(302);
    }

    public function test_regular_deny_update()
    {
        $category = [
            'name' => 'test category update',
            'description' => 'test category desc update',
            'access_level' => 'F'
        ];

        $this->actingAs($this->regular1)
                ->put('admin/categories/' . $this->category1->id, $category)
                ->assertStatus(302);
    }

    public function test_regular_deny_destroy()
    {
        $this->actingAs($this->regular1)
                ->delete('admin/categories/1')
                ->assertStatus(302);
    }


    public function test_author_allow_index()
    {
        $this->actingAs($this->author1)
                ->get('api/categories')
                ->assertStatus(200);
    }

    public function test_author_allow_show()
    {
        $this->actingAs($this->author1)
                ->get('api/categories/' . $this->category1->id)
                ->assertStatus(200);
    }

    public function test_author_allow_tags()
    {
        $this->actingAs($this->author1)
                ->get('api/tags/categories/2')
                ->assertStatus(200);
    }

    public function test_author_allow_comments()
    {
        $this->actingAs($this->author1)
                ->get('api/comments/categories/2')
                ->assertStatus(200);
    }

    public function test_author_allow_pages()
    {
        $this->actingAs($this->author1)
                ->get('api/categories/1/pages')
                ->assertStatus(200);
    }

    public function test_author_deny_store()
    {
        $category = [
            'name' => 'test category',
            'description' => 'test category desc',
            'access_level' => 'F'
        ];

        $this->actingAs($this->author1)
                ->post('admin/categories', $category)
                ->assertStatus(302);
    }

    public function test_author_deny_update()
    {
        $category = [
            'name' => 'test category update',
            'description' => 'test category desc update',
            'access_level' => 'F'
        ];

        $this->actingAs($this->author1)
                ->put('admin/categories/' . $this->category1->id, $category)
                ->assertStatus(302);
    }

    public function test_author_deny_destroy()
    {
        $this->actingAs($this->author1)
                ->delete('admin/categories/1')
                ->assertStatus(302);
    }


    public function test_editor_allow_index()
    {
        $this->actingAs($this->editor1)
                ->get('api/categories')
                ->assertStatus(200);
    }

    public function test_editor_allow_show()
    {
        $this->actingAs($this->editor1)
                ->get('api/categories/' . $this->category1->id)
                ->assertStatus(200);
    }

    public function test_editor_allow_tags()
    {
        $this->actingAs($this->editor1)
                ->get('api/tags/categories/2')
                ->assertStatus(200);
    }

    public function test_editor_allow_comments()
    {
        $this->actingAs($this->editor1)
                ->get('api/comments/categories/2')
                ->assertStatus(200);
    }

    public function test_editor_allow_pages()
    {
        $this->actingAs($this->editor1)
                ->get('api/categories/1/pages')
                ->assertStatus(200);
    }

    public function test_editor_allow_store()
    {
        $category = [
            'name' => 'test category',
            'description' => 'test category desc',
            'access_level' => 'F'
        ];

        $this->actingAs($this->editor1)
                ->post('admin/categories', $category)
                ->assertStatus(201);
    }

    public function test_editor_allow_update()
    {
        $category = [
            'name' => 'test category update',
            'description' => 'test category desc update',
            'access_level' => 'F'
        ];

        $this->actingAs($this->editor1)
                ->put('admin/categories/' . $this->category1->id, $category)
                ->assertStatus(200);
    }

    public function test_editor_deny_destroy()
    {
        $this->actingAs($this->editor1)
                ->delete('admin/categories/1')
                ->assertStatus(302);
    }
}
