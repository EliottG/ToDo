<?php

namespace App\Tests\Functionnal;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
	private $client;
	private $urlGenerator;
	private $userRepository;
	private $taskRepository;

	protected function setUp(): void
	{
		$this->client = self::createClient();
		$this->urlGenerator = $this->client->getContainer()->get('router');
		$this->userRepository = $this->client
			->getContainer()
			->get('doctrine.orm.default_entity_manager')
			->getRepository(User::class);
		$this->taskRepository = $this->client
			->getContainer()
			->get('doctrine.orm.default_entity_manager')
			->getRepository(Task::class);
	}

	public function testListActionSuccessful()
	{
		$this->loginWithUser();
		$crawler = $this->client->request(
			'GET',
			$this->urlGenerator->generate('task_list')
		);
		$this->assertEquals('200', $this->client->getResponse()->getStatusCode());
		$this->assertSelectorTextContains('html', 'Marquer comme faite');
	}

	public function loginWithUser()
	{
		$crawler = $this->client->request('GET', $this->urlGenerator->generate('login'));
		$form = $crawler->selectButton('Se connecter')->form();
		$this->client->submit($form, [
			'_username' => 'email3@mail.com',
			'_password' => 'test'
		]);
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

	public function testListActionFailed()
	{
		$crawler = $this->client->request(
			'GET',
			$this->urlGenerator->generate('task_list')
		);
		$this->assertEquals('302', $this->client->getResponse()->getStatusCode());
		$this->client->followRedirect();
		$this->assertSelectorTextContains('html', 'Se connecter');
	}

	public function testCreateActionSuccessful()
	{
		$this->loginWithUser();
		$crawler = $this->client->request(
			'GET',
			$this->urlGenerator->generate('task_create')
		);

		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
		$form = $crawler->selectButton('Ajouter')->form();
		$this->client->submit($form, [
			'task[title]' => 'task',
			'task[content]' => 'testcontent'
		]);
		$this->assertEquals(302, $this->client->getResponse()->getStatusCode());
		$this->client->followRedirect();
		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
		$this->assertSelectorTextContains('html', 'Superbe');
	}

	public function testEditActionFailed()
	{
		$this->loginWithUser();
		$task = $this->taskRepository->find(4);
		$crawler = $this->client->request(
			'GET',
			$this->urlGenerator->generate('task_edit', ['id' => $task->getId()])
		);

		$this->assertEquals(403, $this->client->getResponse()->getStatusCode());
	}

	public function testEditActionSuccessful()
	{
		$this->loginWithUser();
		$task = $this->taskRepository->findOneBy(['user' => 3]);
		$crawler = $this->client->request(
			'GET',
			$this->urlGenerator->generate('task_edit', ['id' => $task->getId()])
		);

		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
		$form = $crawler->selectButton('Modifier')->form();
		$this->client->submit($form, ['task[title]' => 'update test']);
		$this->assertEquals(302, $this->client->getResponse()->getStatusCode());
		$this->client->followRedirect();
		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
		$this->assertSelectorTextContains('html', 'Superbe');
	}

	public function testToggleTaskActionSuccess()
	{
		$this->loginWithUser();
		$task = $this->taskRepository->findOneBy(['user' => 3]);
		$crawler = $this->client->request(
			'GET',
			$this->urlGenerator->generate('task_toggle', ['id' => $task->getId()])
		);
		$this->assertEquals(302, $this->client->getResponse()->getStatusCode());
		$this->client->followRedirect();
		$this->assertSelectorTextContains('html', 'Superbe');
	}

	public function testToggleTaskActionFailed()
	{
		$this->loginWithUser();
		$task = $this->taskRepository->find(2);
		$this->client->request(
			'GET',
			$this->urlGenerator->generate('task_toggle', ['id' => $task->getId()])
		);
		$this->assertEquals(403, $this->client->getResponse()->getStatusCode());
	}

	public function testDeleteTaskActionSuccess()
	{
		$this->loginWithUser();
		$task = $this->taskRepository->findOneBy(['user' => 3]);
		$crawler = $this->client->request(
			'GET',
			$this->urlGenerator->generate('task_delete', ['id' => $task->getId()])
		);
		$this->assertEquals(302, $this->client->getResponse()->getStatusCode());
		$this->client->followRedirect();
		$this->assertSelectorTextContains('html', 'Superbe');
	}

	public function testDeleteTaskActionFailed()
	{
		$this->loginWithUser();
		$task = $this->taskRepository->find(2);
		$this->client->request(
			'GET',
			$this->urlGenerator->generate('task_delete', ['id' => $task->getId()])
		);
		$this->assertEquals(403, $this->client->getResponse()->getStatusCode());
	}

	public function testListActionAdmin()
	{
		$this->loginWithAdmin();
		$crawler = $this->client->request(
			'GET',
			$this->urlGenerator->generate('task_list')
		);
		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
		$this->assertSelectorTextContains('html', 'Anonyme');
	}

	public function testIsDoneTask()
	{
		$this->loginWithUser();
		$this->client->request('GET', $this->urlGenerator->generate('task_done'));
		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
	}

	public function testEditWithAdminRole()
	{
		$this->loginWithAdmin();
		$task = $this->taskRepository->find(19);
		$crawler = $this->client->request(
			'GET',
			$this->urlGenerator->generate('task_edit', ['id' => $task->getId()])
		);

		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
		$form = $crawler->selectButton('Modifier')->form();
		$this->client->submit($form, ['task[title]' => 'update test']);
		$this->assertEquals(302, $this->client->getResponse()->getStatusCode());
		$this->client->followRedirect();
		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
		$this->assertSelectorTextContains('html', 'Superbe');
	}
}
