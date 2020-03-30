<?php

namespace Tests\Feature;


use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function testHomePageIsWorkingCorrectly()
    {
        $response = $this->actingAs($this->user())->get('/');

        $response->assertSeeText('Home Page');
        $response->assertSeeText('This is a Home Page');
    }

    public function testContactPageIsWorkingCorrectly()
    {
        $response = $this->actingAs($this->user())->get('/contact');

        $response->assertSeeText('Contacts');
        $response->assertSeeText('Hello, this is contacts');
    }
}
