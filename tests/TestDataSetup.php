<?php

namespace Tests;

use App\Category;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestDataSetup extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->generate_categories();
        //$this->generate_pages();
    }

    private function generate_categories()
    {
        // create 3 categories for test
        $this->category1 = factory(Category::class)->create(['parent_id' => null]);
        $this->category2 = factory(Category::class)->create(['parent_id' => null]);

        // and one subcategory under first parent category
        $this->category3 = factory(Category::class)->create(['parent_id' => $this->category1->id]);
    }
}
