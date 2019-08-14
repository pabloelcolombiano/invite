<?php


namespace AppBundle\Tests\Factory;


use AppBundle\Entity\Invitation;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class InvitationFactory extends BaseFactory
{
    /**
     * UserFactory constructor.
     * @param EntityManager $em
     */
    public function __construct($em)
    {
        $this->entity = new Invitation();
        parent::__construct($em);
    }

    public function getDefaultData()
    {
        if (!$this->entity->getSenderId()) {
            $sender = new UserFactory($this->em);
            $sender = $sender->make();
            $this->entity->setSenderId($sender->getId());
        }
        if (!$this->entity->getInvitedId()) {
            $invited = new UserFactory($this->em);
            $invited = $sender->make();
            $this->entity->setInvitedId($invited->getId());
        }
        return [];
    }

    public function withSenderId($id)
    {
        $this->entity->setSenderId($id);
        return $this;
    }

    public function withInvitedId($id)
    {
        $this->entity->setInvitedId($id);
        return $this;
    }
}