<?php

namespace App\Tests\Functional\Controller;

use App\Controller\SecurityController;
use App\Factory\UserFactory;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Attribute\ResetDatabase;

#[ResetDatabase]
class SecurityControllerTest extends WebTestCase
{
    #[Test]
    public function loginPageIsProperlyDisplayed(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSame('Please sign in', $crawler->filter('h1')->text());
        $this->assertSelectorExists('input[type="email"]');
        $this->assertSelectorExists('input[type="password"]');
    }

    #[Test]
    public function userCanLoginWithProperCredentials(): void
    {
        $client = static::createClient();
        $user = UserFactory::new()
            ->email('some@user.com')
            ->create();

        $client->request('GET', '/login');
        $client->submitForm('Sign in', [
            '_username' => $user->getEmail(),
            '_password' => 'password',
        ]);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorNotExists('.alert.alert-danger');
        $this->assertPageTitleSame('Welcome!');
    }

    #[Test]
    public function userCantLoginWithWrongCredentials(): void
    {
        $client = static::createClient();
        $user = UserFactory::new()
            ->email('some@user.com')
            ->create();

        $crawler = $client->request('GET', '/login');
        $client->submitForm('Sign in', [
            '_username' => $user->getEmail(),
            '_password' => 'wrongPassword',
        ]);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.alert.alert-danger');
        $this->assertPageTitleSame('Log in');
    }
}
