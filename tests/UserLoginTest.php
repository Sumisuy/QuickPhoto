<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserLoginTest extends TestCase
{
    /**
     * Check a login panel is available at the given address and
     * contains all necessary inputs and options
     *
     * @author MS
     */
    public function testLoginScreenView()
    {
        $this->visit('/login')
            ->see('E-Mail Address')
            ->see('Password')
            ->see('Login')
            ->see('Forgot Your Password?');
    }

    /**
     * Attempt to login with fake account
     *
     * @author MS
     */
    public function testLoggingInCorrectly()
    {
        $this->visit('/login')
            ->type('fake@address.com', 'email')
            ->type('password', 'password')
            ->press('Login')
            ->seePageIs('/');
    }

    /**
     * Try logging in with no input data to test validation
     *
     * @author MS
     */
    public function testLoginNoInput()
    {
        $this->visit('/login')
            ->press('Login')
            ->see('The email field is required.')
            ->see('The password field is required.');
    }

    /**
     * Try logging in with no email input data to test validation
     *
     * @author MS
     */
    public function testLoginNoEmail()
    {
        $this->visit('/login')
            ->type('password', 'password')
            ->press('Login')
            ->see('The email field is required.');
    }

    /**
     * Try logging in with no password input data to test validation
     *
     * @author MS
     */
    public function testLoginNoPassword()
    {
        $this->visit('/login')
            ->type('fake@address.com', 'email')
            ->press('Login')
            ->see('The password field is required.');
    }

    /**
     * Try logging in with bad email data to test Auth and response
     *
     * @author MS
     */
    public function testLoginBadEmail()
    {
        $this->visit('/login')
            ->type('email@bad.com', 'email')
            ->type('password', 'password')
            ->press('Login')
            ->see('These credentials do not match our records.');
    }

    /**
     * Try logging in with bad password data to test Auth and response
     *
     * @author MS
     */
    public function testLoginBadPassword()
    {
        $this->visit('/login')
            ->type('fake@address.com', 'email')
            ->type('bad', 'password')
            ->press('Login')
            ->see('These credentials do not match our records.');
    }
}
