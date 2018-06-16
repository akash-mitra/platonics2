<?php

namespace Tests;

//use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\BrowserKitTesting\TestCase as BaseTestCase;

abstract class TestCaseBrowser extends BaseTestCase
{
    use CreatesApplication;

    public $baseUrl = 'http://platonics2.app';
}
