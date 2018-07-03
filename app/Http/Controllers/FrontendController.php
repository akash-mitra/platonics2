<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Page;
use App\Category;
use App\Configuration;
use Illuminate\Support\Facades\Cache;

class FrontendController extends Controller
{
    private $menus = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Menu - this is automatically added to any view
        $this->menus = $this->getMenuItems();
    }

    /**
     * Show the application homepage.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        // find out what template needs to be applied
        // for this page and get the respective style
        $template = 'home';
        $slots = $this->getTemplateSlots($template);

        // find out what are the various modules that
        // need to be published with the main contents
        $modules = $this->getModulePositions($template);

        return view('home')
                ->withStyles($slots)
                ->withModules($modules)
                ->withMenus($this->menus)
                ->withParameters(['object_id' => null]);
    }

    public function category($id)
    {
        // find out what template needs to be applied
        // for this page and get the respective style
        $template = 'category';
        $slots = $this->getTemplateSlots($template);

        // find out what are the various modules that
        // need to be published with the main contents
        $modules = $this->getModulePositions($template);

        $category = Category::with('publishedPages')->with('subcategories')->FindOrFail($id);

        return view('categories')
                ->withCategory($category)
                ->withStyles($slots)
                ->withModules($modules)
                ->withMenus($this->menus)
                ->withParameters(['object_id' => null]);
    }

    /**
     * Display the specified page.
     *
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function page($id)
    {
        // find out what template needs to be applied
        // for this page and get the respective style
        $template = 'pages';
        $slots = $this->getTemplateSlots($template);

        // find out what are the various modules that
        // need to be published with the main contents
        $modules = $this->getModulePositions($template);

        $page = Page::where('publish', 'Y')->with('category', 'contents', 'users')->FindOrFail($id);

        return view('pages')
                ->withPage($page)
                ->withStyles($slots)
                ->withModules($modules)
                ->withMenus($this->menus)
                ->withParameters(['object_id' => $page->id]);
    }

    /**
     * Display the specified user.
     *
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function user($id)
    {
        // find out what template needs to be applied
        // for this page and get the respective style
        //$template = 'page';
        //$slots = $this->getTemplateSlots($template);

        // find out what are the various modules that
        // need to be published with the main contents
        //$modules = $this->getModulePositions($template);

        $user = User::with('pages')->where('slug', $id)->first();

        return response()->json($user, 201);

        /*return view('users')
                ->withUser($user)
                ->withStyles($slots)
                ->withModules($modules)
                ->withMenus($this->menus)
                ->withParameters(['object_id' => $page->id]);
        */
    }

    /**
     * Any number of templates can be created for the site.
     * Each template has a specific set of defined styles.
     * This function retrieves the styles for a given template
     */
    private function getTemplateSlots($template = 'home')
    {
        $config = Configuration::getConfig('templates');

        return $config['templates'][$template];

        // if ($template === 'home') {
        //     return [
        //                 'body' => [
        //                         'class' => 'bg-grey-lightest font-sans'
        //                 ],
        //                 'subheader' => [
        //                         'display' => false,
        //                         'class' => 'w-full bg-white border-b'
        //                 ],
        //                 'header' => [
        //                         'display' => true,
        //                         'class' => 'flex w-full bg-white'
        //                 ],
        //                 'left' => [
        //                         'display' => false,
        //                         'class' => 'w-full sm:w-1/4 bg-grey-lightest'
        //                 ],
        //                 'center' => [
        //                         'display' => true,
        //                         'class' => 'w-full sm:w-3/4 bg-grey-lightest'
        //                 ],
        //                 'right' => [
        //                         'display' => false,
        //                         'class' => 'w-full sm:w-1/4 bg-grey-lightest'
        //                 ],
        //                 'footer' => [
        //                         'display' => true,
        //                         'class' => 'w-full bg-white'
        //                 ],
        //         ];
        // }

        // if ($template === 'category') {
        //     return [
        //                 'body' => [
        //                         'class' => 'bg-grey-lightest font-sans'
        //                 ],
        //                 'header' => [
        //                         'display' => true,
        //                         'class' => 'flex w-full bg-purple'
        //                 ],
        //                 'subheader' => [
        //                         'display' => true,
        //                         'class' => 'w-full'
        //                 ],
        //                 'left' => [
        //                         'display' => false,
        //                         'class' => 'w-full sm:w-1/4 bg-grey-lightest'
        //                 ],
        //                 'center' => [
        //                         'display' => true,
        //                         'class' => 'w-full sm:w-3/4 bg-grey-lightest'
        //                 ],
        //                 'right' => [
        //                         'display' => false,
        //                         'class' => 'w-full sm:w-1/4 bg-grey-lightest'
        //                 ],
        //                 'footer' => [
        //                         'display' => true,
        //                         'class' => 'w-full bg-white'
        //                 ],
        //         ];
        // }

        // if ($template === 'page') {
        //     return [
        //                 'body' => [
        //                         'class' => 'bg-grey-lightest font-sans'
        //                 ],
        //                 'header' => [
        //                         'display' => true,
        //                         'class' => 'flex w-full bg-white border-b'
        //                 ],
        //                 'subheader' => [
        //                         'display' => true,
        //                         'class' => 'w-full'
        //                 ],
        //                 'left' => [
        //                         'display' => false,
        //                         'class' => 'w-full sm:w-1/4 bg-grey-lightest'
        //                 ],
        //                 'center' => [
        //                         'display' => true,
        //                         'class' => 'w-full sm:w-3/4 bg-grey-lightest'
        //                 ],
        //                 'right' => [
        //                         'display' => false,
        //                         'class' => 'w-full sm:w-1/4 bg-grey-lightest'
        //                 ],
        //                 'footer' => [
        //                         'display' => true,
        //                         'class' => 'w-full bg-white'
        //                 ],
        //         ];
        // }
    }

    /**
     * modules can be included or excluded based on categories and pages respectively
     */
    private function getModulePositions($contentType, array $inclusionList = [], array $exclusionList = [])
    {
        if ($contentType === 'pages') {
            return ['header' => ['logo-light', 'menu-light'], 'left' => ['related'], 'right' => ['similar'], 'center' => ['comments']];
        }

        if ($contentType === 'category') {
            return ['header' => ['logo-light', 'menu-light']];
        }

        if ($contentType === 'forum') {
            return ['left' => ['latest'], 'right' => ['sticky']];
        }

        if ($contentType === 'home') {
            //     return ['header' => ['logo', 'search', 'login'], 'center' => ['content']];
            return ['header' => ['logo', 'menu']];
        }

        return [];
    }

    private function getMenuItems()
    {
        return Cache::rememberForever('menu', function () {
            return DB::table('categories')->whereNull('parent_id')->pluck('id', 'name');
        });
    }
}
