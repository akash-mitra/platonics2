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

        $noOfEditors = 2;
        $noOfAuthors = 5;
        $noOfRegularUsers = 50;

        // create an admin user
        DB::table('users')->insert([
            'name' => 'Mitra',
            'email' => $adminUserName,
            'password' => bcrypt($adminPassword),
            'type' => 'Admin',
            'slug' => uniqid(mt_rand(), true),
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        ]);

        // create a few editors
        $editorIds = factory(User::class, $noOfEditors)->create([
            'type' => 'Editor'
        ])->pluck('id')->all();

        // create a few authors
        $authorIds = factory(User::class, $noOfAuthors)->create([
            'type' => 'Author'
        ])->pluck('id')->all();

        // create a few users
        $userIds = factory(User::class, $noOfRegularUsers)->create([
            'type' => 'Regular'
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
                                'publish' => random_int(1, 3) != 1 ? 'Y' : 'N',
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

        // create 10 random media
        $media = factory(Media::class, 10)->create([
            'user_id' => $authorIds[random_int(0, count($authorIds) - 1)]
        ]);

        // set s3 Configuration
        DB::table('configurations')->insert([
            'key' => 'storage',
            'value' => serialize(json_encode('{"type":"s3", "key":"AKIAI3SQSEQQEZ662QRA", "secret":"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", "region":"ap-southeast-1", "bucket":"platonics2"}')),
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        ]);


        // populate static permissions table
        $permissions = array( 
            [ 'type' => 'Visitor', 'resource' => 'categories', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Visitor', 'resource' => 'categories', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Visitor', 'resource' => 'comments', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Visitor', 'resource' => 'comments', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Visitor', 'resource' => 'media', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Visitor', 'resource' => 'pages', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Visitor', 'resource' => 'pages', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Visitor', 'resource' => 'pages', 'action' => 'comments', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Visitor', 'resource' => 'tags', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Visitor', 'resource' => 'tags', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Visitor', 'resource' => 'tags', 'action' => 'categories', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Visitor', 'resource' => 'tags', 'action' => 'pages', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Visitor', 'resource' => 'tags', 'action' => 'all', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Regular', 'resource' => 'categories', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Regular', 'resource' => 'categories', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Regular', 'resource' => 'comments', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Regular', 'resource' => 'comments', 'action' => 'store', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Regular', 'resource' => 'comments', 'action' => 'create', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Regular', 'resource' => 'comments', 'action' => 'destroy', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Regular', 'resource' => 'comments', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Regular', 'resource' => 'comments', 'action' => 'update', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Regular', 'resource' => 'comments', 'action' => 'edit', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Regular', 'resource' => 'media', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Regular', 'resource' => 'pages', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Regular', 'resource' => 'pages', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Regular', 'resource' => 'pages', 'action' => 'comments', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'store', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'attach', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'create', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'detach', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'categories', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'pages', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Regular', 'resource' => 'tags', 'action' => 'all', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'categories', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'categories', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'comments', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'comments', 'action' => 'store', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'comments', 'action' => 'create', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'comments', 'action' => 'destroy', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'comments', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'comments', 'action' => 'update', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'comments', 'action' => 'edit', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'media', 'action' => 'store', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'media', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'media', 'action' => 'create', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'media', 'action' => 'destroy', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'media', 'action' => 'update', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'media', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'media', 'action' => 'edit', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'store', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'create', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'update', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'edit', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'publish', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'pages', 'action' => 'comments', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'store', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'attach', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'create', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'detach', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'categories', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'pages', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Author', 'resource' => 'tags', 'action' => 'all', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'categories', 'action' => 'store', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'categories', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'categories', 'action' => 'create', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'categories', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'categories', 'action' => 'update', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'categories', 'action' => 'edit', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'comments', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'comments', 'action' => 'store', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'comments', 'action' => 'create', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'comments', 'action' => 'destroy', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'comments', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'comments', 'action' => 'update', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'comments', 'action' => 'edit', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'store', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'create', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'destroy', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'update', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'media', 'action' => 'edit', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'store', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'create', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'update', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'edit', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'publish', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'pages', 'action' => 'comments', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'store', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'attach', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'create', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'detach', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'update', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'edit', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'categories', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'pages', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Editor', 'resource' => 'tags', 'action' => 'all', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'home', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'home', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'home', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'home', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'store', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'create', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'update', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'destroy', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'categories', 'action' => 'edit', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'comments', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'comments', 'action' => 'store', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'comments', 'action' => 'create', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'comments', 'action' => 'destroy', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'comments', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'comments', 'action' => 'update', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'comments', 'action' => 'edit', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'store', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'create', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'destroy', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'update', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'edit', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],          
            [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'absolute', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'relative', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'media', 'action' => 'optimize', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],            
            [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'store', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'create', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'destroy', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'update', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'edit', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'publish', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'pages', 'action' => 'comments', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'store', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'attach', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'create', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'detach', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'destroy', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'update', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'edit', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'categories', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'pages', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],  
            [ 'type' => 'Admin', 'resource' => 'tags', 'action' => 'all', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'configurations', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'configurations', 'action' => 'store', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'configurations', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'configurations', 'action' => 'destroy', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'users', 'action' => 'index', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'users', 'action' => 'show', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'users', 'action' => 'update', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'users', 'action' => 'destroy', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            [ 'type' => 'Admin', 'resource' => 'users', 'action' => 'edit', 'permission' => 'allow', 'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ],
            
        );
        
        DB::table('permissions')->insert($permissions);


    }

    // end of run
}
