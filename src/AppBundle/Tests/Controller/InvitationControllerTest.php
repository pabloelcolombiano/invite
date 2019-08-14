<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Invitation;
use AppBundle\Entity\User;
use AppBundle\Tests\Factory\InvitationFactory;
use AppBundle\Tests\Factory\UserFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Repository\RepositoryFactory;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Tests\Controller\TestControllerTrait;

class InvitationControllerTest extends WebTestCase
{
    /**
     * @var EntityManager
     */
    public $em;

    public $Invitations;

    /**
     * @var Client
     */
    public $client;

    public function setUp()
    {
        static::bootKernel();
        $doctrine = static::$kernel->getContainer()->get('doctrine');
        $this->em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->Invitations = $doctrine->getRepository(Invitation::class);
        $this->client = static::createClient();
    }

    public function testInvitationSentWithValidToken()
    {
        $sender = new UserFactory($this->em);
        $sender = $sender->make();

        $invited = new UserFactory($this->em);
        $invited = $invited->make();

        $senderId = $sender->getId();
        $invitedId = $invited->getId();
        $senderToken = $sender->getToken();

        $this->client->request('GET', "/api/send/$senderId/$invitedId/$senderToken");

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $response = "Invitation sent to User $invitedId";
        $this->assertEquals(json_encode(compact('response')), $this->client->getResponse()->getContent());
    }

    public function testInvitationSentWithInvalidToken()
    {

        $sender = new UserFactory($this->em);
        $sender = $sender->make();

        $invited = new UserFactory($this->em);
        $invited = $invited->make();

        $senderId = $sender->getId();
        $invitedId = $invited->getId();
        $invalidSenderToken = $sender->getToken() . 'a';

        $this->client->request('GET', "/api/send/$senderId/$invitedId/$invalidSenderToken");

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $response = 'User Id - Token Combination not valid';
        $this->assertEquals(json_encode(compact('response')), $this->client->getResponse()->getContent());
    }

    public function testInvitationAccepted()
    {
        $invited = new UserFactory($this->em);
        $invited = $invited->make();
        $invitedId = $invited->getId();
        $token = $invited->getToken();

        $invitation = new InvitationFactory($this->em);
        $invitation = $invitation->withInvitedId($invited->getId())->make([
            'status' => Invitation::STATUS_NEW,
        ]);
        $invitationId = $invitation->getId();

        $this->client->request('GET', "/api/accept/$invitedId/$invitationId/$token");

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $response = 'Invitation ' . Invitation::STATUS_ACCEPTED;
        $this->assertEquals(json_encode(compact('response')), $this->client->getResponse()->getContent());
    }

    public function testInvitationDeclined()
    {
        $invited = new UserFactory($this->em);
        $invited = $invited->make();
        $invitedId = $invited->getId();
        $token = $invited->getToken();

        $invitation = new InvitationFactory($this->em);
        $invitation = $invitation->withInvitedId($invited->getId())->make([
            'status' => Invitation::STATUS_NEW,
        ]);
        $invitationId = $invitation->getId();

        $this->client->request('GET', "/api/decline/$invitedId/$invitationId/$token");

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $response = 'Invitation ' . Invitation::STATUS_DECLINED;
        $this->assertEquals(json_encode(compact('response')), $this->client->getResponse()->getContent());
    }

    public function testInvitationCanceled()
    {
        $sender = new UserFactory($this->em);
        $sender = $sender->make();
        $senderId = $sender->getId();
        $token = $sender->getToken();

        $invitation = new InvitationFactory($this->em);
        $invitation = $invitation->withInvitedId($sender->getId())->make([
            'status' => Invitation::STATUS_NEW,
        ]);
        $invitationId = $invitation->getId();

        $this->client->request('GET', "/api/cancel/$senderId/$invitationId/$token");

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $response = 'Invitation ' . Invitation::STATUS_CANCELED;
        $this->assertEquals(json_encode(compact('response')), $this->client->getResponse()->getContent());
    }
}
