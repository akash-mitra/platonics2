<?php

namespace Tests;

use App\Category;
use App\User;
use App\Page;
use App\Content;
use App\Tag;
use App\Comment;
use App\Media;
use App\Permission;
use DB;
use Illuminate\Support\Facades\Cache;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestDataSetup extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->generate_categories();
        $this->generate_pages();
        $this->generate_tags();
        $this->generate_comments();
        $this->generate_media();
        $this->generate_users();
        $this->generate_permissions();
        $this->generate_configurations();
    }

    private function generate_categories()
    {
        // create 3 categories for test
        $this->category1 = factory(Category::class)->create(['parent_id' => null]);
        $this->category2 = factory(Category::class)->create(['parent_id' => null]);

        // and one subcategory under first parent category
        $this->category3 = factory(Category::class)->create(['parent_id' => $this->category1->id]);
    }

    private function generate_pages()
    {
        // create a author
        $this->author1 = factory(User::class)->create(['type' => 'Author']);

        // create a editor
        $this->editor1 = factory(User::class)->create(['type' => 'Editor']);

        // create a regular
        $this->regular1 = factory(User::class)->create(['type' => 'Regular']);

        // create 3 pages for test
        $this->page1 = factory(Page::class)->create([
            'access_level' => 'F',
            'publish' => 'Y',
            'user_id' => $this->author1->id,
            'category_id' => $this->category1->id,
        ]);

        $this->page2 = factory(Page::class)->create([
            'access_level' => 'M',
            'publish' => 'Y',
            'user_id' => $this->author1->id,
            'category_id' => $this->category1->id,
        ]);

        $this->page3 = factory(Page::class)->create([
            'access_level' => 'M',
            'publish' => 'Y',
            'user_id' => $this->author1->id,
            'category_id' => $this->category1->id,
        ]);

        // create 3 page contents for test
        $this->content1 = factory(Content::class)->create(['page_id' => $this->page1->id]);
        $this->content2 = factory(Content::class)->create(['page_id' => $this->page2->id]);
        $this->content3 = factory(Content::class)->create(['page_id' => $this->page3->id]);
    }

    private function generate_tags()
    {
        // create 3 tags for test
        $this->tag1 = factory(Tag::class)->create(['user_id' => $this->author1->id]);
        $this->tag2 = factory(Tag::class)->create(['user_id' => $this->author1->id]);
        $this->tag3 = factory(Tag::class)->create(['user_id' => $this->author1->id]);

        // attach tags to the category and page
        $this->category2->tags()->attach($this->tag2, ['user_id' => 1]);
        $this->page2->tags()->attach($this->tag2, ['user_id' => 1]);
    }

    private function generate_comments()
    {
        // create 3 comments for test
        $this->comment1 = factory(Comment::class)->make(['user_id' => $this->author1->id]);
        $this->comment2 = factory(Comment::class)->make(['user_id' => $this->author1->id]);
        $this->comment3 = factory(Comment::class)->make(['user_id' => $this->author1->id]);

        // attach comments to the category and page
        $this->page1->comments()->save($this->comment1, ['user_id' => 1]);
        $this->category2->comments()->save($this->comment2, ['user_id' => 1]);
        $this->page3->comments()->save($this->comment3, ['user_id' => 1]);
    }

    private function generate_media()
    {
        // create 3 media for test
        $this->media1 = factory(Media::class)->create(['user_id' => $this->author1->id]);
        $this->media2 = factory(Media::class)->create(['user_id' => $this->author1->id]);
        $this->media3 = factory(Media::class)->create(['user_id' => $this->author1->id]);
    }

    private function generate_users()
    {
        // create a admin
        $this->admin1 = factory(User::class)->create(['type' => 'Admin']);
        // create a editor
        $this->editor1 = factory(User::class)->create(['type' => 'Editor']);
        // create a author
        $this->author1 = factory(User::class)->create(['type' => 'Author']);
        // create a regular user
        $this->regular1 = factory(User::class)->create(['type' => 'Regular']);
    }

    private function generate_configurations()
    {
        DB::table('configurations')
            ->insert(['key' => 'hello', 
                'value' => serialize(json_encode('{"bgcolor": "white","layout": "3-columns","modules": {"left": ["adsense"],"right": ["popular", "related"]}}')),
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')]);
        Cache::forever('hello', '{"bgcolor": "white","layout": "3-columns","modules": {"left": ["adsense"],"right": ["popular", "related"]}}');

        DB::table('configurations')
            ->insert(['key' => 'world', 
                'value' => serialize(json_encode('{"bgcolor": "black","layout": "3-columns","modules": {"left": ["adsense"],"right": ["popular", "related"]}}')),
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')]);
        Cache::forever('world', '{"bgcolor": "black","layout": "3-columns","modules": {"left": ["adsense"],"right": ["popular", "related"]}}');
    }

    private function generate_permissions()
    {
        // Populate static Permissions table
            
        // CATEGORY
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'categories', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'categories', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'categories', 'action' => 'tags' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'categories', 'action' => 'comments' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'categories', 'action' => 'pages' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'categories', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'categories', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'categories', 'action' => 'tags' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'categories', 'action' => 'comments' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'categories', 'action' => 'pages' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'categories', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'categories', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'categories', 'action' => 'tags' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'categories', 'action' => 'comments' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'categories', 'action' => 'pages' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'categories', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'categories', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'categories', 'action' => 'tags' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'categories', 'action' => 'comments' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'categories', 'action' => 'pages' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'categories', 'action' => 'home' ] ); 
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'categories', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'categories', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'categories', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'categories', 'action' => 'update' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'tags' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'comments' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'pages' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'home' ] ); 
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'update' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'destroy' ] );
        
        
        // PAGE
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'pages', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'pages', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'pages', 'action' => 'tags' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'pages', 'action' => 'comments' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'pages', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'pages', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'pages', 'action' => 'tags' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'pages', 'action' => 'comments' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'tags' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'comments' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'home' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'update' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'publish' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'tags' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'comments' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'home' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'update' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'publish' ] );     
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'tags' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'comments' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'home' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'update' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'publish' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'destroy' ] );
        

        // TAG
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'tags', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'tags', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'tags', 'action' => 'categories' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'tags', 'action' => 'pages' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'tags', 'action' => 'all' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'tags', 'action' => 'attach' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'tags', 'action' => 'detach' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'tags', 'action' => 'fullattach' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'categories' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'pages' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'all' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'attach' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'detach' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'fullattach' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'categories' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'pages' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'all' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'attach' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'detach' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'fullattach' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'home' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'update' ] );   
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'categories' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'pages' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'all' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'attach' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'detach' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'fullattach' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'home' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'update' ] ); 
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'categories' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'pages' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'all' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'attach' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'detach' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'fullattach' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'home' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'update' ] ); 
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'destroy' ] );


        // COMMENT
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'comments', 'action' => 'home' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'comments', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'comments', 'action' => 'update' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'comments', 'action' => 'destroy' ] );
        

        // MEDIA
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'media', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'media', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'media', 'action' => 'absolute' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'media', 'action' => 'relative' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'media', 'action' => 'optimize' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'media', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'media', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'media', 'action' => 'absolute' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'media', 'action' => 'relative' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'media', 'action' => 'optimize' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'media', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'media', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'media', 'action' => 'absolute' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'media', 'action' => 'relative' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'media', 'action' => 'optimize' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'media', 'action' => 'home' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'media', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'media', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'media', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'media', 'action' => 'update' ] ); 
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'absolute' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'relative' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'optimize' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'home' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'update' ] );   
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'absolute' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'relative' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'optimize' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'home' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'update' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'destroy' ] );


        // CONFIGURATION
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'configurations', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'configurations', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'configurations', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'configurations', 'action' => 'destroy' ] );
   
        
        // USER
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'users', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'users', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'users', 'action' => 'pages' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'users', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'users', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'users', 'action' => 'pages' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'users', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'users', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'users', 'action' => 'pages' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'users', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'users', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'users', 'action' => 'pages' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'users', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'users', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'users', 'action' => 'pages' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'users', 'action' => 'home' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'users', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'users', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'users', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'users', 'action' => 'update' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'users', 'action' => 'type' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'users', 'action' => 'destroy' ] );

        
    }
}
