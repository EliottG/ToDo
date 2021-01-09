<?php

namespace App\Tests\Functionnal;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private $client;
    private $urlGenerator;
    private $userRepository;
    private $taskRepository;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->urlGenerator = $this->client
            ->getContainer()
            ->get('router');
        $this->userRepository = $this->client->getContainer()
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository(User::class);
        $this->taskRepository = $this->client->getContainer()
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository(Task::class);
    }

    private function loginWithUser(): void
    {
        $crawler = $this->client->request('GET', $this->urlGenerator->generate('login'));
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, ['_username' => 'User3', '_password' => 'test']);
    }

    public function testCreateAction()
    {
        $crawler = $this->client->request('GET', $this->urlGenerator->generate('user_create'));
        $form = $crawler->selectButton('Ajouter')->form();
        $this->client->submit($form, [
            "user[username]" => "testUser",
            "user[password][first]" => "test",
            "user[password][second]" => "test",
            "user[email]" => "emailtest@mail.com"
        ]);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->followRedirect();
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testEditUserSuccessful()
    {
        $this->loginWithUser();
        $user = $this->userRepository->find(3);
        $crawler = $this->client
            ->request('GET', $this->urlGenerator
                ->generate('user_edit', ['id' => $user->getId()]));
        $form = $crawler->selectButton('Modifier')->form();
        $this->client->submit($form, ["user[username]" => "UserForTest"]);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testEditUserFailed()
    {
        $this->loginWithUser();
        $user = $this->userRepository->find(1);
        $crawler = $this->client
            ->request('GET', $this->urlGenerator
                ->generate('user_edit', ['id' => 2]));
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testFailVoterUserPage()
    {
        $this->client->request('GET', '/users/2/edit');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

}