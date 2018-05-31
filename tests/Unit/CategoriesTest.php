<?php

namespace Tests\Unit;

use Tests\TestDataSetup;

class CategoriesTest extends TestDataSetup
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_show_returns_expected_structure()
    {
        $this->get('/api/categories/' . $this->category1->id)
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

    public function test_index_returns_all()
    {
        $content = $this->get('/api/categories')->decodeResponseJson();

        $this->assertEquals($content['length'], count(\App\Category::all()));
    }

    public function test_that_store_method_can_persist_data()
    {
        $category = [
            'name' => 'test category1',
            'description' => 'desc',
            'access_level' => 'F'
        ];

        $this->post('/api/categories', $category)
            ->assertStatus(201)
            ->assertJsonFragment($category);
    }
}
