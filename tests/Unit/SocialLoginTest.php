<?php

namespace Tests\Unit;

use Tests\TestCaseBrowser;
use Mockery;
use Socialite;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SocialLoginTest extends TestCaseBrowser
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Total Test Cases: 1
     */

    /**
     * Positive Test Cases: 1
     */

    // public function test_google_login()
    // {
    //     $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');

    //     $abstractUser
    //         ->shouldReceive('getId')
    //         ->andReturn(rand())
    //         ->shouldReceive('getName')
    //         ->andReturn(str_random(10))
    //         ->shouldReceive('getEmail')
    //         ->andReturn(str_random(10) . '@gmail.com')
    //         ->shouldReceive('getAvatar')
    //         ->andReturn('https://en.gravatar.com/userimage');

    //     $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
    //     $provider->shouldReceive('user')->andReturn($abstractUser);

    //     Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

    //     $this->visit('/login/google/callback')
    //         ->seePageIs('/');
    // }

    // public function test_facebook_login()
    // {
    //     $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');

    //     $abstractUser
    //         ->shouldReceive('getId')
    //         ->andReturn(rand())
    //         ->shouldReceive('getName')
    //         ->andReturn(str_random(10))
    //         ->shouldReceive('getEmail')
    //         ->andReturn(str_random(10) . '@gmail.com')
    //         ->shouldReceive('getAvatar')
    //         ->andReturn('https://en.gravatar.com/userimage');

    //     $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
    //     $provider->shouldReceive('user')->andReturn($abstractUser);

    //     Socialite::shouldReceive('driver')->with('facebook')->andReturn($provider);

    //     $this->visit('/login/facebook/callback')
    //         ->seePageIs('/');
    // }

    // public function test_twitter_login()
    // {
    //     $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');

    //     $abstractUser
    //         ->shouldReceive('getId')
    //         ->andReturn(rand())
    //         ->shouldReceive('getName')
    //         ->andReturn(str_random(10))
    //         ->shouldReceive('getEmail')
    //         ->andReturn(str_random(10) . '@gmail.com')
    //         ->shouldReceive('getAvatar')
    //         ->andReturn('https://en.gravatar.com/userimage');

    //     $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
    //     $provider->shouldReceive('user')->andReturn($abstractUser);

    //     Socialite::shouldReceive('driver')->with('twitter')->andReturn($provider);

    //     $this->visit('/login/twitter/callback')
    //         ->seePageIs('/');
    // }

    // public function test_github_login()
    // {
    //     $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');

    //     $abstractUser
    //         ->shouldReceive('getId')
    //         ->andReturn(rand())
    //         ->shouldReceive('getName')
    //         ->andReturn(str_random(10))
    //         ->shouldReceive('getEmail')
    //         ->andReturn(str_random(10) . '@gmail.com')
    //         ->shouldReceive('getAvatar')
    //         ->andReturn('https://en.gravatar.com/userimage');

    //     $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
    //     $provider->shouldReceive('user')->andReturn($abstractUser);

    //     Socialite::shouldReceive('driver')->with('github')->andReturn($provider);

    //     $this->visit('/login/github/callback')
    //         ->seePageIs('/');
    // }
}
