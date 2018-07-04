<?php

namespace Tests\Unit;

use App\Configuration;
use Tests\TestDataSetup;

class ConfigurationTest extends TestDataSetup
{
    /**
     * Total Test Cases: 8
     */

    /**
     * Positive Test Cases: 6
     */
    
    public function test_index_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('admin/configs')
                ->assertStatus(200)
                ->assertJsonStructure([ 
                    'length',
                    'data' => [
                        '*' => [
                            'id',
                            'key',
                            'value',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]);
    }

    public function test_index_returns_expected_length()
    {
        $response = $this->actingAs($this->admin1)->get('admin/configs')->decodeResponseJson();
        $this->assertEquals($response['length'], 3);
    }

    public function test_show_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('admin/configs/' . 'hello')
                ->assertStatus(200)
                ->assertJsonFragment([
                    '{"bgcolor": "white","layout": "3-columns","modules": {"left": ["adsense"],"right": ["popular", "related"]}}'
                ]);
    }

    public function test_store_can_persist_data()
    {
        $config = [
            'key' => 'HelloKey',
            'value' => '{"Brown": "Fox", "Hello": "World"}'
            ];

        $this->actingAs($this->admin1)
                ->post('admin/configs', $config)
                ->assertStatus(200)
                ->assertJsonFragment([ 'status' => 'success' ]);
    }

    public function test_update_can_persist_data()
    {
        $config = [
            'key' => 'HelloKey.Brown',
            'value' => 'Dog'
        ];
        
        $this->actingAs($this->admin1)
                ->post('admin/configs', $config)
                ->assertStatus(200)
                ->assertJsonFragment([ 'status' => 'success' ]);
    }

    public function test_destroy_can_delete_data()
    {
        $this->actingAs($this->admin1)
                ->delete('admin/configs/world')
                ->assertStatus(200)
                ->assertJsonFragment([ 'status' => 1 ]);
    }

    
    /**
     * Negative Test Cases: 2
     */
    
    public function test_show_error_invalid_id()
    {
        $this->actingAs($this->admin1)
                ->get('admin/configs/blabla')
                ->assertStatus(200)
                ->assertJsonFragment([ 'Not Found' ]);
    }

    public function test_destroy_error_invalid_id()
    {
        $this->actingAs($this->admin1)
                ->delete('admin/configs/blablah')
                ->assertStatus(200)
                ->assertJsonFragment([ 'status' => 0 ]);
    }
}
