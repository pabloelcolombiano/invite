<?php


namespace AppBundle\Entity\Behavior;


trait TimeStampBehavior
{
    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $modified;

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created = null)
    {
        $this->created = $created ?? new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * @param mixed $modified
     */
    public function setModified($modified = null)
    {
        $this->modified = $modified ?? new \DateTime();
    }
}