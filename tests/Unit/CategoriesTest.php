<?php

namespace Tests\Unit;

use App\Category;
use Tests\TestDataSetup;

class CategoriesTest extends TestDataSetup
{
    /**
     * Total Test Cases: 30
     */

    /**
     * Positive Test Cases: 6
     */

    public function test_index_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('/categories')
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
        $response = $this->actingAs($this->admin1)->get('/categories')->decodeResponseJson();
        $this->assertEquals($response['length'], count(Category::all()));
    }

    public function test_show_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('/categories/' . $this->category1->id)
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
                ->post('/categories', $category)
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
                ->put('/categories/' . $this->category1->id, $category)
                ->assertStatus(200)
                ->assertJsonFragment($category);
    }

    public function test_destroy_can_delete_data()
    {
        $this->actingAs($this->admin1)
                ->delete('/categories/1')
                ->assertStatus(200)
                ->assertJsonFragment([$this->category1->name]);
    }

    
    /**
     * Negative Test Cases: 4
     */

    public function test_show_error_invalid_id()
    {
        $this->actingAs($this->admin1)
                ->get('/categories/108')
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
                ->post('/categories', $category)
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
                ->put('/categories/' . $this->category1->id, $category)
                ->assertStatus(302);
    }

    public function test_destroy_error_invalid_id()
    {
        $this->actingAs($this->admin1)
                ->delete('/categories/108')
                ->assertStatus(404);
    }


    /**
     * User Type Permissions Cases: 5 * 4 = 20
     */

    public function test_visitor_allow_index()
    {
        $this->get('/categories')
                ->assertStatus(200);
    }

    public function test_visitor_allow_show()
    {
        $this->get('/categories/' . $this->category1->id)
                ->assertStatus(200);
    }

    public function test_visitor_deny_store()
    {
        $category = [
            'name' => 'test category',
            'description' => 'test category desc',
            'access_level' => 'F'
        ];

        $this->post('/categories', $category)
                ->assertStatus(302);
    }

    public function test_visitor_deny_update()
    {
        $category = [
            'name' => 'test category update',
            'description' => 'test category desc update',
            'access_level' => 'F'
        ];

        $this->put('/categories/' . $this->category1->id, $category)
                ->assertStatus(302);
    }

    public function test_visitor_deny_destroy()
    {
        $this->delete('/categories/1')
                ->assertStatus(302);
    }


    public function test_regular_allow_index()
    {
        $this->actingAs($this->regular1)
                ->get('/categories')
                ->assertStatus(200);
    }

    public function test_regular_allow_show()
    {
        $this->actingAs($this->regular1)
                ->get('/categories/' . $this->category1->id)
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
                ->post('/categories', $category)
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
                ->put('/categories/' . $this->category1->id, $category)
                ->assertStatus(302);
    }

    public function test_regular_deny_destroy()
    {
        $this->actingAs($this->regular1)
                ->delete('/categories/1')
                ->assertStatus(302);
    }


    public function test_author_allow_index()
    {
        $this->actingAs($this->author1)
                ->get('/categories')
                ->assertStatus(200);
    }

    public function test_author_allow_show()
    {
        $this->actingAs($this->author1)
                ->get('/categories/' . $this->category1->id)
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
                ->post('/categories', $category)
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
                ->put('/categories/' . $this->category1->id, $category)
                ->assertStatus(302);
    }

    public function test_author_deny_destroy()
    {
        $this->actingAs($this->author1)
                ->delete('/categories/1')
                ->assertStatus(302);
    }


    public function test_editor_allow_index()
    {
        $this->actingAs($this->editor1)
                ->get('/categories')
                ->assertStatus(200);
    }

    public function test_editor_allow_show()
    {
        $this->actingAs($this->editor1)
                ->get('/categories/' . $this->category1->id)
                ->assertStatus(200);
    }

    public function test_editor_deny_store()
    {
        $category = [
            'name' => 'test category',
            'description' => 'test category desc',
            'access_level' => 'F'
        ];

        $this->actingAs($this->editor1)
                ->post('/categories', $category)
                ->assertStatus(201);
    }

    public function test_editor_deny_update()
    {
        $category = [
            'name' => 'test category update',
            'description' => 'test category desc update',
            'access_level' => 'F'
        ];

        $this->actingAs($this->editor1)
                ->put('/categories/' . $this->category1->id, $category)
                ->assertStatus(200);
    }

    public function test_editor_deny_destroy()
    {
        $this->actingAs($this->editor1)
                ->delete('/categories/1')
                ->assertStatus(302);
    }
}
