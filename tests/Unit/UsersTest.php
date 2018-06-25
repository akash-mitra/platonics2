<?php

namespace Tests\Unit;

use App\User;
use Tests\TestDataSetup;

class UsersTest extends TestDataSetup
{
    /**
     * Total Test Cases: 8
     */

    /**
     * Positive Test Cases: 5
     */

    public function test_index_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('/users')
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
        $response = $this->actingAs($this->admin1)->get('/users')->decodeResponseJson();
        $this->assertEquals($response['length'], count(User::all()));
    }

    public function test_show_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('/users/' . $this->author1->id)
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

    public function test_update_can_persist_page_data()
    {
        $user = [
            'type' => 'Author'
        ];

        $this->actingAs($this->admin1)
                ->put('/users/' . $this->regular1->id, $user)
                ->assertStatus(200)
                ->assertJsonFragment($user);
    }

    public function test_destroy_can_delete_data()
    {
        $this->actingAs($this->admin1)
                ->delete('/users/5')
                ->assertStatus(200)
                ->assertJsonFragment([$this->editor1->name]);
    }

    
    /**
     * Negative Test Cases: 3
     */

    public function test_show_error_invalid_id()
    {
        $this->actingAs($this->admin1)
                ->get('/users/108')
                ->assertStatus(404);
    }

    public function test_update_error_invalid_data()
    {
        $user = [
            'type' => 'SuperUser'
        ];
        
        $this->actingAs($this->admin1)
                ->put('/users/' . $this->regular1->id, $user)
                ->assertStatus(302);
    }

    public function test_destroy_error_invalid_id()
    {
        $this->actingAs($this->admin1)
                ->delete('/users/108')
                ->assertStatus(404);
    }
}
