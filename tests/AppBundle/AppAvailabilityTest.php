<?php
namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppAvailabilityTest extends WebTestCase {

    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url,$expectedStatus)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertEquals($expectedStatus, $client->getResponse()->getStatusCode());
    }

    public function urlProvider()
    {
        yield ['/',302];
        yield ['/login',200];
        yield['/users/create', 200];
        yield['/tasks/create', 302];
        yield['/tasks',302];
        
    }
}