<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use AppBundle\Entity\Behavior\TimeStampBehavior;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM;

class User extends BaseUser
{
    use TimeStampBehavior;

    protected $id;

    protected $token;

    public function __construct()
    {
        $this->setToken($this->makeToken());
        $this->setCreated();
        $this->setModified();
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    public function makeToken()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < 255; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }
}