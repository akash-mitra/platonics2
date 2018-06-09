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

    private function generate_permissions()
    {
        // Add Permissions Entries
        /*$this->permission1 = factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'categories', 'action' => 'index']);
        $this->permission2 = factory(Permission::class)->create(['type' => 'Author', 'resource' => 'categories', 'action' => 'index']);
        $this->permission3 = factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'categories', 'action' => 'index']);
        $this->permission4 = factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'categories', 'action' => 'show']);
        $this->permission5 = factory(Permission::class)->create(['type' => 'Author', 'resource' => 'categories', 'action' => 'show']);
        $this->permission6 = factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'categories', 'action' => 'show']);
        $this->permission7 = factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'categories', 'action' => 'store']);
        $this->permission8 = factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'categories', 'action' => 'update']);
        $this->permission9 = factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'categories', 'action' => 'destroy']);*/

        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'categories', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'categories', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'comments', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'comments', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'media', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'pages', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'pages', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'pages', 'action' => 'comments' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'tags', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Visitor', 'resource' => 'tags', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'categories', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'categories', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'comments', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'comments', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'comments', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'comments', 'action' => 'destroy' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'comments', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'comments', 'action' => 'update' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'comments', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'media', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'pages', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'pages', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'pages', 'action' => 'comments' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'attach' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'detach' ] );
        factory(Permission::class)->create( [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'categories', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'categories', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'comments', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'comments', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'comments', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'comments', 'action' => 'destroy' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'comments', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'comments', 'action' => 'update' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'comments', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'media', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'media', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'media', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'media', 'action' => 'destroy' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'media', 'action' => 'update' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'media', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'media', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'update' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'publish' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'comments' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'attach' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'detach' ] );
        factory(Permission::class)->create( [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'categories', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'categories', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'categories', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'categories', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'categories', 'action' => 'update' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'categories', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'comments', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'comments', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'comments', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'comments', 'action' => 'destroy' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'comments', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'comments', 'action' => 'update' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'comments', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'destroy' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'update' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'update' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'publish' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'comments' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'attach' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'detach' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'update' ] );
        factory(Permission::class)->create( [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'home' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'home' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'home' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'home' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'update' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'destroy' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'comments', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'comments', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'comments', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'comments', 'action' => 'destroy' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'comments', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'comments', 'action' => 'update' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'comments', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'destroy' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'update' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'destroy' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'update' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'edit' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'publish' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'comments' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'index' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'store' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'attach' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'create' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'detach' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'show' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'destroy' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'update' ] );
        factory(Permission::class)->create( [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'edit' ] );
    }
}
