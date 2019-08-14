<?php


namespace AppBundle\Tests\Factory;

use Doctrine\ORM\EntityManager;

abstract class BaseFactory
{
    public $entity;

    public $em;

    abstract function getDefaultData();

    /**
     * BaseFactory constructor.
     * @param EntityManager $em
     */
    public function __construct($em)
    {
        $this->em = $em;
    }

    public function make(array $data = [])
    {
        $data = array_merge($this->getDefaultData(), $data);
        foreach ($data as $field => $value) {
            $method = 'set'.ucfirst($field);
            $this->entity->{$method}($value);
        }
        $this->entity->setCreated();
        $this->entity->setModified();
//        var_dump($this->entity);die();
        $this->em->persist($this->entity);
        $this->em->flush();
        return $this->entity;
    }
}