<?php

/*
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2013-2024 Alexander Weissman & Louis Charette
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Tests\App\Controller;

use UserFrosting\App\MyApp;
use UserFrosting\Sprinkle\Account\Database\Models\User;
use UserFrosting\Sprinkle\Core\Testing\RefreshDatabase;
use UserFrosting\Testing\TestCase;

class UserRedirectedToAboutTest extends TestCase
{
    use RefreshDatabase;
    
    protected string $mainSprinkle = MyApp::class;

    public function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
    }

    public function testPage(): void
    {
        $request = $this->createJsonRequest('GET', '/account/sign-in');
        $response = $this->handleRequest($request);
        $this->assertResponseStatus(200, $response);
        $this->assertStringContainsString('src="/assets/images/cupcake', (string) $response->getBody());
        $this->assertStringContainsString('<script src="/assets/page.sign-in.js"></script>', (string) $response->getBody());
    }

    public function testLogin(): void
    {
        /** @var User */
        $user = User::factory([
            'password' => 'test'
        ])->create();

        // Create request with method and url and fetch response
        $request = $this->createJsonRequest('POST', '/account/login', [
            'user_name' => $user->user_name,
            'password'  => 'test',
        ]);
        $response = $this->handleRequest($request);

        // Assert response status & body
        $this->assertJsonResponse([], $response);
        $this->assertResponseStatus(200, $response);

        // Assert Event Redirect
        $this->assertSame('/about', $response->getHeaderLine('UF-Redirect'));
    }
}
