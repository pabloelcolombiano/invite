<?php


namespace AppBundle\Tests\Factory;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class UserFactory extends BaseFactory
{
    /**
     * UserFactory constructor.
     * @param EntityManager $em
     */
    public function __construct($em)
    {
        $this->entity = new User();
        parent::__construct($em);
    }

    public function getDefaultData()
    {
        $rand = rand(1,100000);
        return [
            'username' => 'user' . $rand,
            'email' => 'user' . $rand . '@invite.test',
            'password' => 'secret',
        ];
    }
}