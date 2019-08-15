<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Behavior\TimeStampBehavior;
use Doctrine\ORM;

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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="invitations")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     */
    private $sender;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="invitations")
     * @ORM\JoinColumn(name="invited_id", referencedColumnName="id")
     */
    private $invited;

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

    /**
     * Populate a new invitation with the new status
     * @param int $senderId
     * @param int $invitedId
     */
    public function create(int $senderId, int $invitedId)
    {
        $this->setSenderId($senderId);
        $this->setInvitedId($invitedId);
        $this->setStatus(self::STATUS_NEW);
        $this->setCreated();
        $this->setModified();
    }

    public function getFrontendColor()
    {
        switch ($this->getStatus()) {
            case self::STATUS_CANCELED:
            case self::STATUS_DECLINED:
                $textColor = 'red';
                break;
            case self::STATUS_ACCEPTED:
                $textColor = 'green';
                break;
            default:
                $textColor = '';
                break;
        }
        return $textColor;
    }

    /**
     * @return mixed
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param mixed $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return mixed
     */
    public function getInvited()
    {
        return $this->invited;
    }

    /**
     * @param mixed $invited
     */
    public function setInvited($invited)
    {
        $this->invited = $invited;
    }
}

