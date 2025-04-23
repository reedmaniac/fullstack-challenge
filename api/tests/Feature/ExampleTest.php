<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Make sure we can load the users endpoint for API
     *
     * @return void
     */
    public function testSuccessfulResponseForApiUsers()
    {
        $response = $this->get('/api/users');

        $response->assertStatus(200);
    }

    /**
     * Make sure we can load single user for API
     *
     * @return void
     */
    public function testSuccessfulResponseForSingleApiUser()
    {
        $users = User::factory(1)->create();
        $response = $this->get('/api/users/'.$users->first()->id);

        $response->assertStatus(200);
    }
}
