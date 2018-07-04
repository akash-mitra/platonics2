<?php

namespace Tests\Unit;

use App\User;
use Tests\TestDataSetup;

class UsersTest extends TestDataSetup
{
    /**
     * Total Test Cases: 11
     */

    /**
     * Positive Test Cases: 8
     */
    
    public function test_index_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('api/users')
                ->assertStatus(200)
                ->assertJsonStructure([ 
                    'length',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'type',
                            'email',
                            'slug',
                            'avatar',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]);
    }

    public function test_index_returns_expected_length()
    {
        $response = $this->actingAs($this->admin1)->get('api/users')->decodeResponseJson();
        $this->assertEquals($response['length'], count(User::all()));
    }

    public function test_show_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('api/users/' . $this->author1->id)
                ->assertStatus(200)
                ->assertJsonStructure([
                    'id',
                    'name',
                    'type',
                    'email',
                    'slug',
                    'avatar',
                    'created_at',
                    'updated_at'
                ]);
    }

    public function test_store_can_persist_data()
    {
        $user = [
            'name' => 'newuser',
            'email' => 'newuser@gmail.com',
            'password' => 'helloworld',
            'type' => 'Regular'
        ];

        $this->actingAs($this->admin1)
                ->post('admin/users', $user)
                ->assertStatus(201)
                ->assertJsonFragment(['name' => 'newuser']);
    }

    public function test_update_can_persist_data()
    {
        $user = [
            'name' => 'UpdatedName',
            'email' => 'xyz@gmail.com',
            'password' => 'helloworld',
            'type' => 'Author'
        ];

        $this->actingAs($this->admin1)
                ->put('admin/users/' . $this->regular1->id, $user)
                ->assertStatus(200)
                ->assertJsonFragment(['name' => 'UpdatedName']);
    }

    public function test_type_can_persist_user_data()
    {
        $user = [
            'type' => 'Author'
        ];

        $this->actingAs($this->admin1)
                ->put('admin/users/' . $this->regular1->id . '/type', $user)
                ->assertStatus(200)
                ->assertJsonFragment($user);
    }

    public function test_destroy_can_delete_data()
    {
        $this->actingAs($this->admin1)
                ->delete('admin/users/5')
                ->assertStatus(200)
                ->assertJsonFragment([$this->editor1->name]);
    }

    public function test_pages_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('api/users/' . $this->author1->slug . '/pages')
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
                            'draft',
                            'created_at',
                            'updated_at',
                            'category' => [
                                'id',
                                'parent_id',
                                'name',
                                'description',
                                'access_level',
                                'created_at',
                                'updated_at'
                            ],
                            'tags' => [
                                '*' => [
                                    'id',
                                    'user_id',
                                    'name',
                                    'description',
                                    'created_at',
                                    'updated_at',
                                    'pivot' => [
                                        'taggable_id',
                                        'tag_id',
                                        'taggable_type'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]);
    }

    
    /**
     * Negative Test Cases: 3
     */
    
    public function test_show_error_invalid_id()
    {
        $this->actingAs($this->admin1)
                ->get('api/users/108')
                ->assertStatus(404);
    }

    public function test_type_error_invalid_data()
    {
        $user = [
            'type' => 'SuperUser'
        ];
        
        $this->actingAs($this->admin1)
                ->put('admin/users/' . $this->regular1->id . '/type', $user)
                ->assertStatus(302);
    }

    public function test_destroy_error_invalid_id()
    {
        $this->actingAs($this->admin1)
                ->delete('admin/users/108')
                ->assertStatus(404);
    }
}
