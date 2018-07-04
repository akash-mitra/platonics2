<?php

use App\Tag;
use App\User;
use App\Category;
use App\Page;
use App\Comment;
use App\Content;
use App\Media;
use App\Permission;
use App\GoogleAnalytics;
use App\Configuration;
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

                    // randomly select 75% of these categories and put
                    // random number of comments in them

                    if (random_int(1, 4) != 1) {
                        $comments = factory(Comment::class, random_int(0, 4))->make([
                            'user_id' => $userIds[random_int(0, count($userIds) - 1)],
                        ]);

                        $category->comments()->saveMany($comments);
                    }

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

                                factory(GoogleAnalytics::class)->create([
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

        // Seed Default Configurations
        $key1 = 'templates';
        $value1 = ['home' => ['body' => ['class' => ''], 'header' => ['display' => true, 'class' => 'flex w-full bg-white border-b'], 'subheader' => ['display' => false, 'class' => ''], 'left' => ['display' => false, 'class' => ''], 'center' => ['display' => true, 'class' => 'w-full sm:w-3/4'], 'right' => ['display' => false, 'class' => ''], 'bottom' => ['display' => false, 'class' => ''], 'footer' => ['display' => true, 'class' => 'w-full bg-white']], 'pages' => ['body' => ['class' => ''], 'header' => ['display' => true, 'class' => 'flex w-full bg-white border-b'], 'subheader' => ['display' => true, 'class' => 'w-full'], 'left' => ['display' => false, 'class' => ''], 'center' => ['display' => true, 'class' => 'w-full sm:w-3/4'], 'right' => ['display' => false, 'class' => ''], 'bottom' => ['display' => false, 'class' => ''], 'footer' => ['display' => true, 'class' => 'w-full']], 'category' => ['body' => ['class' => ''], 'header' => ['display' => true, 'class' => 'flex w-full bg-purple'], 'subheader' => ['display' => true, 'class' => 'w-full'], 'left' => ['display' => false, 'class' => ''], 'center' => ['display' => true, 'class' => 'w-full sm:w-3/4'], 'right' => ['display' => false, 'class' => ''], 'bottom' => ['display' => false, 'class' => ''], 'footer' => ['display' => true, 'class' => 'w-full bg-white']], 'profile' => ['body' => ['class' => ''], 'header' => ['display' => false, 'class' => ''], 'subheader' => ['display' => false, 'class' => ''], 'left' => ['display' => false, 'class' => ''], 'center' => ['display' => false, 'class' => ''], 'right' => ['display' => false, 'class' => ''], 'bottom' => ['display' => false, 'class' => ''], 'footer' => ['display' => false, 'class' => '']], 'forum' => ['body' => ['class' => ''], 'header' => ['display' => false, 'class' => ''], 'subheader' => ['display' => false, 'class' => ''], 'left' => ['display' => false, 'class' => ''], 'center' => ['display' => false, 'class' => ''], 'right' => ['display' => false, 'class' => ''], 'bottom' => ['display' => false, 'class' => ''], 'footer' => ['display' => false, 'class' => '']], 'forumhome' => ['body' => ['class' => ''], 'header' => ['display' => false, 'class' => ''], 'subheader' => ['display' => false, 'class' => ''], 'left' => ['display' => false, 'class' => ''], 'center' => ['display' => false, 'class' => ''], 'right' => ['display' => false, 'class' => ''], 'bottom' => ['display' => false, 'class' => ''], 'footer' => ['display' => false, 'class' => '']]];

        Configuration::setConfig($key1, $value1);

        $key2 = 'storage';
        $value2 = ['type' => 'local'];
        Configuration::setConfig($key2, $value2);

        // Populate static Permissions table

        // CATEGORY
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'categories', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'categories', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'categories', 'action' => 'tags']);
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'categories', 'action' => 'comments']);
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'categories', 'action' => 'pages']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'categories', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'categories', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'categories', 'action' => 'tags']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'categories', 'action' => 'comments']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'categories', 'action' => 'pages']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'categories', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'categories', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'categories', 'action' => 'tags']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'categories', 'action' => 'comments']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'categories', 'action' => 'pages']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'categories', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'categories', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'categories', 'action' => 'tags']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'categories', 'action' => 'comments']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'categories', 'action' => 'pages']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'categories', 'action' => 'home']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'categories', 'action' => 'create']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'categories', 'action' => 'edit']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'categories', 'action' => 'store']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'categories', 'action' => 'update']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'categories', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'categories', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'categories', 'action' => 'tags']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'categories', 'action' => 'comments']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'categories', 'action' => 'pages']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'categories', 'action' => 'home']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'categories', 'action' => 'create']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'categories', 'action' => 'edit']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'categories', 'action' => 'store']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'categories', 'action' => 'update']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'categories', 'action' => 'destroy']);

        // PAGE
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'pages', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'pages', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'pages', 'action' => 'tags']);
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'pages', 'action' => 'comments']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'pages', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'pages', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'pages', 'action' => 'tags']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'pages', 'action' => 'comments']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'pages', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'pages', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'pages', 'action' => 'tags']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'pages', 'action' => 'comments']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'pages', 'action' => 'home']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'pages', 'action' => 'create']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'pages', 'action' => 'edit']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'pages', 'action' => 'store']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'pages', 'action' => 'update']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'pages', 'action' => 'publish']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'pages', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'pages', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'pages', 'action' => 'tags']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'pages', 'action' => 'comments']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'pages', 'action' => 'home']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'pages', 'action' => 'create']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'pages', 'action' => 'edit']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'pages', 'action' => 'store']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'pages', 'action' => 'update']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'pages', 'action' => 'publish']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'pages', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'pages', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'pages', 'action' => 'tags']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'pages', 'action' => 'comments']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'pages', 'action' => 'home']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'pages', 'action' => 'create']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'pages', 'action' => 'edit']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'pages', 'action' => 'store']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'pages', 'action' => 'update']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'pages', 'action' => 'publish']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'pages', 'action' => 'destroy']);

        // TAG
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'tags', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'tags', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'tags', 'action' => 'categories']);
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'tags', 'action' => 'pages']);
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'tags', 'action' => 'all']);
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'tags', 'action' => 'attach']);
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'tags', 'action' => 'detach']);
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'tags', 'action' => 'fullattach']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'tags', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'tags', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'tags', 'action' => 'categories']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'tags', 'action' => 'pages']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'tags', 'action' => 'all']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'tags', 'action' => 'attach']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'tags', 'action' => 'detach']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'tags', 'action' => 'fullattach']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'tags', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'tags', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'tags', 'action' => 'categories']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'tags', 'action' => 'pages']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'tags', 'action' => 'all']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'tags', 'action' => 'attach']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'tags', 'action' => 'detach']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'tags', 'action' => 'fullattach']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'tags', 'action' => 'home']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'tags', 'action' => 'create']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'tags', 'action' => 'edit']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'tags', 'action' => 'store']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'tags', 'action' => 'update']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'tags', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'tags', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'tags', 'action' => 'categories']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'tags', 'action' => 'pages']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'tags', 'action' => 'all']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'tags', 'action' => 'attach']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'tags', 'action' => 'detach']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'tags', 'action' => 'fullattach']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'tags', 'action' => 'home']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'tags', 'action' => 'create']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'tags', 'action' => 'edit']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'tags', 'action' => 'store']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'tags', 'action' => 'update']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'tags', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'tags', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'tags', 'action' => 'categories']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'tags', 'action' => 'pages']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'tags', 'action' => 'all']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'tags', 'action' => 'attach']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'tags', 'action' => 'detach']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'tags', 'action' => 'fullattach']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'tags', 'action' => 'home']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'tags', 'action' => 'create']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'tags', 'action' => 'edit']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'tags', 'action' => 'store']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'tags', 'action' => 'update']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'tags', 'action' => 'destroy']);

        // COMMENT
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'comments', 'action' => 'store']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'comments', 'action' => 'update']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'comments', 'action' => 'store']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'comments', 'action' => 'update']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'comments', 'action' => 'store']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'comments', 'action' => 'update']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'comments', 'action' => 'home']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'comments', 'action' => 'store']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'comments', 'action' => 'update']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'comments', 'action' => 'destroy']);

        // MEDIA
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'media', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'media', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'media', 'action' => 'absolute']);
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'media', 'action' => 'relative']);
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'media', 'action' => 'optimize']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'media', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'media', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'media', 'action' => 'absolute']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'media', 'action' => 'relative']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'media', 'action' => 'optimize']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'media', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'media', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'media', 'action' => 'absolute']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'media', 'action' => 'relative']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'media', 'action' => 'optimize']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'media', 'action' => 'home']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'media', 'action' => 'create']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'media', 'action' => 'edit']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'media', 'action' => 'store']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'media', 'action' => 'update']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'media', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'media', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'media', 'action' => 'absolute']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'media', 'action' => 'relative']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'media', 'action' => 'optimize']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'media', 'action' => 'home']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'media', 'action' => 'create']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'media', 'action' => 'edit']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'media', 'action' => 'store']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'media', 'action' => 'update']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'media', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'media', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'media', 'action' => 'absolute']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'media', 'action' => 'relative']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'media', 'action' => 'optimize']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'media', 'action' => 'home']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'media', 'action' => 'create']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'media', 'action' => 'edit']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'media', 'action' => 'store']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'media', 'action' => 'update']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'media', 'action' => 'destroy']);

        // CONFIGURATION
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'configurations', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'configurations', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'configurations', 'action' => 'store']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'configurations', 'action' => 'destroy']);

        // USER
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'users', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'users', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'users', 'action' => 'pages']);
        factory(Permission::class)->create(['type' => 'Visitor', 'resource' => 'users', 'action' => 'draft']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'users', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'users', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'users', 'action' => 'pages']);
        factory(Permission::class)->create(['type' => 'Regular', 'resource' => 'users', 'action' => 'draft']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'users', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'users', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'users', 'action' => 'pages']);
        factory(Permission::class)->create(['type' => 'Author', 'resource' => 'users', 'action' => 'draft']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'users', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'users', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'users', 'action' => 'pages']);
        factory(Permission::class)->create(['type' => 'Editor', 'resource' => 'users', 'action' => 'draft']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'users', 'action' => 'show']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'users', 'action' => 'index']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'users', 'action' => 'pages']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'users', 'action' => 'draft']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'users', 'action' => 'home']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'users', 'action' => 'create']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'users', 'action' => 'edit']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'users', 'action' => 'store']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'users', 'action' => 'update']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'users', 'action' => 'type']);
        factory(Permission::class)->create(['type' => 'Admin', 'resource' => 'users', 'action' => 'destroy']);
    }

    // end of run
}
