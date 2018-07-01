<?php

namespace Tests\Unit;

use App\Page;
use Tests\TestDataSetup;

class PagesTest extends TestDataSetup
{
    /**
     * Total Test Cases: 13
     */

    /**
     * Positive Test Cases: 9
     */
    
    public function test_index_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('api/pages')
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
                            'slug',
                            'users' => [
                                'name',
                                'type',
                                'slug',
                                'avatar',
                                'created_at',
                                'updated_at'
                            ],
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

    public function test_index_returns_expected_length()
    {
        $response = $this->actingAs($this->admin1)->get('api/pages')->decodeResponseJson();
        $this->assertEquals($response['length'], count(Page::all()));
    }

    public function test_show_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('api/pages/' . $this->page1->id)
                ->assertStatus(200)
                ->assertJsonStructure([
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
                    'slug',
                    'metrics' => [
                        'page_id',
                        'page_views', 
                        'adsense_revenue'
                    ]
                ]);
    }

    public function test_store_can_persist_data()
    {
        $page = [
            'category_id' => $this->category1->id,
            'title' => 'test page',
            'access_level' => 'F',
            'body' => 'test page contents body'
        ];

        $this->actingAs($this->admin1)
                ->post('admin/pages', $page)
                ->assertStatus(201)
                ->assertJsonFragment([
                    'category_id' => $this->category1->id,
                    'title' => 'test page',
                    'access_level' => 'F'
                ]);
    }

    public function test_store_can_persist_content_data()
    {
        $page = [
            'category_id' => $this->category1->id,
            'title' => 'test page content',
            'access_level' => 'F',
            'body' => 'test page contents persist body'
        ];

        $response = $this->actingAs($this->admin1)
                            ->post('admin/pages', $page)->decodeResponseJson();

        $body = Page::find($response['id'])->contents->body;
        $this->assertEquals($body, $page['body']);
    }

    public function test_update_can_persist_data()
    {
        $page = [
            'category_id' => $this->category1->id,
            'title' => 'test page update',
            'access_level' => 'F',
            'body' => 'test page contents body update'
        ];

        $this->actingAs($this->admin1)
                ->put('admin/pages/' . $this->page1->id, $page)
                ->assertStatus(200)
                ->assertJsonFragment([
                    'category_id' => $this->category1->id,
                    'title' => 'test page update',
                    'access_level' => 'F',
                ]);
    }

    public function test_destroy_can_delete_data()
    {
        $this->actingAs($this->admin1)
                ->delete('admin/pages/1')
                ->assertStatus(200)
                ->assertJsonFragment([$this->page1->title]);
    }

    public function test_publish_can_persist_data()
    {
        $page = [
            'category_id' => $this->category1->id,
            'title' => 'test page update',
            'access_level' => 'F',
            'body' => 'test page contents body update',
        ];

        $this->actingAs($this->admin1)
                ->put('admin/pages/' . $this->page1->id . '/publish', $page)
                ->assertStatus(200)
                ->assertJsonFragment([
                    'category_id' => $this->category1->id,
                    'title' => 'test page update',
                    'access_level' => 'F',
                    'publish' => 'N',
                ]);
    }

    public function test_comments_returns_expected_structure()
    {
        $this->actingAs($this->admin1)
                ->get('api/comments/pages/' . $this->page1->id)
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
    

    /**
     * Negative Test Cases: 4
     */
    
    public function test_show_error_invalid_id()
    {
        $this->actingAs($this->admin1)
                ->get('api/pages/108')
                ->assertStatus(404);
    }

    public function test_store_error_invalid_data()
    {
        $page = [
            'category_id' => $this->category1->id,
            'title' => 'test page',
            'access_level' => 'X',
            'body' => 'test page contents body'
        ];

        $this->actingAs($this->admin1)
                ->post('admin/pages', $page)
                ->assertStatus(302);
                //->assertJsonFragment(["message"=> "The given data was invalid."]);
    }

    public function test_update_error_invalid_data()
    {
        $page = [
            'category_id' => $this->category1->id,
            'title' => 'test page',
            'access_level' => 'X',
            'body' => 'test page contents body'
        ];

        $this->actingAs($this->admin1)
                ->put('admin/pages/' . $this->page1->id, $page)
                ->assertStatus(302);
    }

    public function test_destroy_error_invalid_id()
    {
        $this->actingAs($this->admin1)
                ->delete('admin/pages/108')
                ->assertStatus(404);
    }
}
