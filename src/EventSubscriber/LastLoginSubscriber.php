<?php

namespace App\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use App\Entity\User;

class LastLoginSubscriber implements EventSubscriberInterface
{

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        /** @var User $user */
        $user = $event->getAuthenticationToken()->getUser();
        $user->setLastLoginAt(new \DateTimeImmutable());

        $this->entityManager->flush();
        dump($user);
    }

    public static function getSubscribedEvents()
    {
        return [
            'security.interactive_login' => 'onSecurityInteractiveLogin',
        ];
    }
}
