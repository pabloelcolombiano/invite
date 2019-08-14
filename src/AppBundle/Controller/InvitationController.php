<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Invitation;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Annotation\Route;

class InvitationController extends Controller
{
    /**
     * @var string
     */
    private $response;

    /**
     * This action sends an invitation from user $sendId to user $invitedId
     * making use of its authorization $token
     * @Route("/api/send/{senderId}/{invitedId}/{token}")
     */
    public function send(int $senderId, int $invitedId, string $token = null)
    {
        if ($this->_tokenIsValid($senderId, $token) && $this->_invitedExists($invitedId)) {

            $invitation = new Invitation();
            $invitation->create($senderId, $invitedId);

            $this->getDoctrine()->getManager()->persist($invitation);
            $this->getDoctrine()->getManager()->flush();

            $this->response = "Invitation sent to User $invitedId";
        }
        return $this->json(['response' => $this->response]);
    }

    /**
     * @Route("/api/cancel/{senderId}/{invitationId}/{token}")
     */
    public function cancel(int $senderId, int $invitationId, string $token = null)
    {
        if ($this->_tokenIsValid($senderId, $token)) {
            /** @var Invitation $invitation */
            $invitation = $this->getDoctrine()->getRepository(Invitation::class)->find($invitationId);
            $invitation->setStatus($invitation::STATUS_CANCELED);

            $this->getDoctrine()->getManager()->persist($invitation);
            $this->getDoctrine()->getManager()->flush();

            $this->response = "Invitation " . $invitation->getStatus();
        }
        return $this->json(['response' => $this->response]);
    }

    /**
     * @Route("/api/accept/{invitedId}/{invitationId}/{token}")
     */
    public function accept(int $invitedId, int $invitationId, string $token = null)
    {
        if ($this->_tokenIsValid($invitedId, $token)) {
            /** @var Invitation $invitation */
            $invitation = $this->getDoctrine()->getRepository(Invitation::class)->find($invitationId);
//            var_dump($invitation);
            $invitation->setStatus($invitation::STATUS_ACCEPTED);

            $this->getDoctrine()->getManager()->persist($invitation);
            $this->getDoctrine()->getManager()->flush();

            $this->response = "Invitation " . $invitation->getStatus();
        }
        return $this->json(['response' => $this->response]);
    }

    /**
     * @Route("/api/decline/{invitedId}/{invitationId}/{token}")
     */
    public function decline(int $invitedId, $invitationId, string $token = null)
    {
        if ($this->_tokenIsValid($invitedId, $token)) {
            /** @var Invitation $invitation */
            $invitation = $this->getDoctrine()->getRepository(Invitation::class)->find($invitationId);
            $invitation->setStatus($invitation::STATUS_DECLINED);

            $this->getDoctrine()->getManager()->persist($invitation);
            $this->getDoctrine()->getManager()->flush();

            $this->response = "Invitation " . $invitation->getStatus();
        }
        return $this->json(['response' => $this->response]);
    }

    /**
     * @Route("/invitations", name="invitations")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $userId = $this->getUser()->getId();

        $invitationsSent = $this->getDoctrine()->getRepository(Invitation::class)
            ->findBy([
                'sender_id' => $userId,
            ], ['created' => 'DESC']);

        $invitationsReceived = $this->getDoctrine()->getRepository(Invitation::class)
            ->findBy([
                'invited_id' => $userId,
            ], ['created' => 'DESC']);

        return $this->render('invitation/index.html.twig', compact('invitationsSent', 'invitationsReceived', 'userId'));
    }

    /**
     * Checks that the userId sending the request and the user token provided match
     * @param int $senderId
     * @param string $token
     * @return object|null
     */
    private function _tokenIsValid(int $senderId, string $token)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'id' => $senderId,
            'token' => $token ?? $this->getUser()->token,
        ]);
        if (!$user) {
            $this->response = 'User Id - Token Combination not valid';
        }
        return $user;
    }

    private function _invitedExists($id)
    {
        if (!$this->getDoctrine()->getRepository(User::class)->find($id)) {
            $this->response = "User with id $id not found";
            return false;
        }
        return true;
    }
}
