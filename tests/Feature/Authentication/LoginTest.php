<?php

namespace Tests\Feature\Authentication;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CreatesModels;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use CreatesModels;
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->generateUser();
    }

    /**
     * A basic feature test example.
     */
    public function testLoginIsSuccessful(): void
    {
        $response = $this->post(
            route('auth.login'),
            [
                'email' => $this->user->email,
                'password' => 'password',
            ]
        );

        $response->assertOk()
            ->assertJsonStructure(['user', 'token'])
            ->assertJson(['user' => $this->user->toArray()]);

        // Make new request with generated token
        $newResponse = $this->withToken($response->json('token'))
            ->get(route('events.index'));

        // Make sure new request is successful.
        $newResponse->assertOk();
    }

    /**
     * A basic feature test example.
     */
    public function testLoginReturnsUnprocessable(): void
    {
        $response = $this->post(
            route('auth.login'),
            []
        );

        $response->assertUnprocessable()
            ->assertInvalid(['email', 'password']);
    }

    /**
     * A basic feature test example.
     */
    public function testLoginFailsWhenEmailIsIncorrect(): void
    {
        $response = $this->post(
            route('auth.login'),
            [
                'email' => 'email@example.com',
                'password' => 'password'
            ]
        );

        $response->assertUnauthorized()
            ->assertJsonPath('message', __('auth.failed'));
    }

    /**
     * A basic feature test example.
     */
    public function testLoginFailsWhenPasswordIsIncorrect(): void
    {
        $response = $this->post(
            route('auth.login'),
            [
                'email' => $this->user->email,
                'password' => 'wrong-password'
            ]
        );

        $response->assertUnauthorized()
            ->assertJsonPath('message', __('auth.failed'));
    }
}
