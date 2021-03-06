<?php

namespace App\Tests\Functionnal;

use App\Entity\User;
use App\Tests\LoginTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
	private $client;
	private $userRepository;
	/**
	 * @var object|\Symfony\Bundle\FrameworkBundle\Routing\Router|null
	 */
	private $urlGenerator;

	protected function setUp(): void
	{
		$this->client = static::createClient();
		$this->userRepository = $this->client
			->getContainer()
			->get('doctrine.orm.default_entity_manager')
			->getRepository(User::class);
		$this->urlGenerator = $this->client->getContainer()->get('router');
	}

	public function loginWithAdmin()
	{
		$crawler = $this->client->request('GET', $this->urlGenerator->generate('login'));
		$form = $crawler->selectButton('Se connecter')->form();
		$this->client->submit($form, [
			'_username' => 'email1@mail.com',
			'_password' => 'test'
		]);
	}

	public function testIndexAdmin()
	{
		$this->loginWithAdmin();

		$this->client->request('GET', $this->urlGenerator->generate('admin_user_list'));

		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
	}

	public function testEditUser()
	{
		$this->loginWithAdmin();
		$user = $this->userRepository->findOneBy([]);

		$crawler = $this->client->request(
			'GET',
			$this->urlGenerator->generate('admin_user_edit', ['id' => $user->getId()])
		);

		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
		$form = $crawler->selectButton('Modifier')->form();
		$this->client->submit($form, ['admin_user_edit[roles][0]' => 'ROLE_ADMIN']);

		$this->assertEquals(302, $this->client->getResponse()->getStatusCode());

		$this->client->followRedirect();

		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
		$this->assertSelectorTextContains('html', 'Superbe');
	}
}
