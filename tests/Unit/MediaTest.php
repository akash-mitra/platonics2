<?php

namespace Tests\Unit;

use App\Media;
use Tests\TestDataSetup;

class MediaTest extends TestDataSetup
{
    /**
     * Total Test Cases: 10
     */

    /**
     * Positive Test Cases: 6
     */

    public function test_index_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('/api/media')
                ->assertStatus(200)
                ->assertJsonStructure([ 
                    'length',
                    'data' => [
                        '*' => [
                            'id',
                            'user_id',
                            'base_path',
                            'filename',
                            'name',
                            'type',
                            'size',
                            'optimized',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]);
    }

    public function test_index_returns_expected_length()
    {
        $response = $this->actingAs($this->admin1)->get('/api/media')->decodeResponseJson();
        $this->assertEquals($response['length'], count(Media::all()));
    }

    public function test_show_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('/api/media/' . $this->media1->id)
                ->assertStatus(200)
                ->assertJsonStructure([
                    'id',
                    'user_id',
                    'base_path',
                    'filename',
                    'name',
                    'type',
                    'size',
                    'optimized',
                    'created_at',
                    'updated_at'
                ]);
    }

    public function test_store_can_persist_data()
    {
        $media = [
            'base_path' => 'http://test.com',
            'filename' => '600x300',
            'name' => 'test filename',
            'type' => 'png'
        ];

        $this->actingAs($this->admin1)
                ->post('/api/media', $media)
                ->assertStatus(201)
                ->assertJsonFragment($media);
    }

    public function test_update_can_persist_data()
    {
        $media = [
            'base_path' => 'http://test.com',
            'filename' => '600x300',
            'name' => 'test filename update',
            'type' => 'bmp'
        ];

        $this->actingAs($this->admin1)
                ->put('/api/media/' . $this->media1->id, $media)
                ->assertStatus(200)
                ->assertJsonFragment($media);
    }

    public function test_destroy_can_delete_data()
    {
        $this->actingAs($this->admin1)
                ->delete('/api/media/1')
                ->assertStatus(200)
                ->assertJsonFragment([$this->media1->name]);
    }

    
    /**
     * Negative Test Cases: 4
     */

    public function test_show_error_invalid_id()
    {
        $this->actingAs($this->admin1)
                ->get('/api/media/108')
                ->assertStatus(404);
    }

    public function test_store_error_invalid_data()
    {
        $media = [
            'base_path' => 'http://test.com',
            'filename' => '600x300',
            'name' => 'test filename'
        ];

        $this->actingAs($this->admin1)
                ->post('/api/media', $media)
                ->assertStatus(302);
                //->assertJsonFragment(['message' => 'The given data was invalid.']);
    }

    public function test_update_error_invalid_data()
    {
        $media = [
            'base_path' => 'http://test.com',
            'filename' => '600x300',
            'name' => 'test filename update'
        ];
        
        $this->actingAs($this->admin1)
                ->put('/api/media/' . $this->media1->id, $media)
                ->assertStatus(302);
    }

    public function test_destroy_error_invalid_id()
    {
        $this->actingAs($this->admin1)
                ->delete('/api/media/108')
                ->assertStatus(404);
    }
}
