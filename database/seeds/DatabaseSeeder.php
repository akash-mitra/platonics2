<?php

use App\Tag;
use App\User;
use App\Category;
use App\Page;
use App\Comment;
use App\Content;
use App\Media;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // setup
        $adminUserName = 'mitra@platonics.com';
        $adminPassword = 'secret';

        // $noOfEditors = 2;
        $noOfAuthors = 5;
        $noOfRegularUsers = 50;

        // create an admin user
        DB::table('users')->insert([
            'name' => 'Mitra',
            'email' => $adminUserName,
            'password' => bcrypt($adminPassword),
            'type' => 'Admin'
        ]);

        // create a few users
        $userIds = factory(User::class, $noOfRegularUsers)->create([
            'type' => 'Regular'
        ])->pluck('id')->all();

        // create a few authors
        $authorIds = factory(User::class, $noOfRegularUsers)->create([
            'type' => 'Author'
        ])->pluck('id')->all();

        // create 20 random tags
        $tags = factory(Tag::class, 20)->create([
            'user_id' => 1
        ])->pluck('id')->all();

        // create a few parent level categories
        $parentCategories = factory(Category::class, 5)->create([
            // 30% contents are protected (M), rest are free (F)
            'access_level' => random_int(1, 3) != 1 ? 'F' : 'M'
        ])
        // and for each of these parent level categories, create a few child categories
        ->each(function ($parentCategory) use ($authorIds, $userIds, $tags) {
            $categories = factory(Category::class, random_int(1, 10))->create([
                    'access_level' => random_int(1, 4) != 1 ? 'F' : 'M',
                    'parent_id' => $parentCategory->id
                ])

                //then add few pages under those children categories
                ->each(function ($category) use ($authorIds, $userIds, $tags) {
                    // attach some random tags to the category
                    shuffle($tags);
                    $someTagsForCategory = array_slice($tags, 0, 5);
                    $category->tags()->attach($someTagsForCategory, ['user_id' => 1]);

                    $maxPagePerCategory = 8;

                    $pages = factory(Page::class, random_int(0, $maxPagePerCategory))
                            ->create([
                                // 30% contents are protected (M), rest are free (F)
                                'access_level' => random_int(1, 3) != 3 ? 'F' : 'M',

                                // written by one of the authors previously created
                                'user_id' => $authorIds[random_int(0, count($authorIds) - 1)],

                                'category_id' => $category->id
                            ])

                            ->each(function ($page) use ($userIds, $someTagsForCategory) {
                                factory(Content::class)->create([
                                    'page_id' => $page->id
                                ]);

                                // randomly select 75% of these pages and put
                                // random number of comments in them

                                if (random_int(1, 4) != 1) {
                                    $comments = factory(Comment::class, random_int(0, 4))->make([
                                        'user_id' => $userIds[random_int(0, count($userIds) - 1)],
                                    ]);

                                    $page->comments()->saveMany($comments);
                                }

                                // randomly select 75% of these pages and put
                                // random number of tags in them

                                if (random_int(1, 4) != 1) {
                                    shuffle($someTagsForCategory);
                                    $selectTags = array_slice($someTagsForCategory, 0, 2);

                                    $page->tags()->attach($selectTags, [
                                        'user_id' => $userIds[random_int(0, count($userIds) - 1)]
                                    ]);
                                }
                            });
                });
            // end of each category
        });
        // end of each parent category

        // create 30 random media
        $media = factory(Media::class, 30)->create([
            'user_id' => $authorIds[random_int(0, count($authorIds) - 1)]
        ]); 
    }

    // end of run
}
