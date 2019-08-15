<?php


namespace AppBundle\DataFixtures;

use AppBundle\Entity\Invitation;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $numberOfUsers = 4;
        $userIds = [];
        for ($i = 0; $i < $numberOfUsers; $i++) {
            $user = new User();
            $user->setUsername("user$i");
            $user->setEmail("user$i@invite.test");
            $password = $this->encoder->encodePassword($user, 'invite');
            $user->setPassword($password);
            $user->setEnabled(true);
            $manager->persist($user);
            $userIds[] = $user->getId();
        }
        $manager->flush();

        $users = $manager->getRepository(User::class)->findAll();
        $numberOfInvitations = 100;
        for ($i = 0; $i < $numberOfInvitations; $i++) {
            $invitation = new Invitation();
            $senderId = $users[rand(0,$numberOfUsers-1)]->getId();
            $invitedId = $users[rand(0,$numberOfUsers-1)]->getId();
            if ($senderId !== $invitedId) {
                $invitation->create($senderId, $invitedId);
                $manager->persist($invitation);
            }
        }
        $manager->flush();
    }
}