<?php

namespace Tests;

use App\Category;
use App\User;
use App\Page;
use App\Content;
use App\Tag;
use App\Comment;
use App\Media;

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
            'user_id' => $this->author1->id,
            'category_id' => $this->category1->id,
        ]);

        $this->page2 = factory(Page::class)->create([
            'access_level' => 'M',
            'user_id' => $this->author1->id,
            'category_id' => $this->category1->id,
        ]);

        $this->page3 = factory(Page::class)->create([
            'access_level' => 'M',
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
}
