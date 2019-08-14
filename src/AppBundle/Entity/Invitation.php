<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Behavior\TimeStampBehavior;

/**
 * Invitation
 */
class Invitation
{
    use TimeStampBehavior;

    const STATUS_NEW = 'new';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';
    const STATUS_CANCELED = 'canceled';

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $sender_id;

    /**
     * @var int
     */
    private $invited_id;

    /**
     * @var string
     */
    private $status;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set senderId
     *
     * @param integer $senderId
     *
     * @return Invitation
     */
    public function setSenderId($senderId)
    {
        $this->sender_id = $senderId;

        return $this;
    }

    /**
     * Get senderId
     *
     * @return int
     */
    public function getSenderId()
    {
        return $this->sender_id;
    }

    /**
     * Set invitedId
     *
     * @param integer $invitedId
     *
     * @return Invitation
     */
    public function setInvitedId($invitedId)
    {
        $this->invited_id = $invitedId;

        return $this;
    }

    /**
     * Get invitedId
     *
     * @return int
     */
    public function getInvitedId()
    {
        return $this->invited_id;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Invitation
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function create(int $senderId, int $invitedId)
    {
        $this->setSenderId($senderId);
        $this->setInvitedId($invitedId);
        $this->setStatus(self::STATUS_NEW);
        $this->setCreated();
        $this->setModified();
    }
}

