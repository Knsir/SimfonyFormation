<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Psr\Log\LoggerInterface;


class LoginFailExceptionListener
{

    private $logger; 

    public function __construct(LoggerInterface $logger){
        $this->logger = $logger;
    }

    public function onSymfonyComponentSecurityHttpEventLoginFailureEvent(LoginFailureEvent $event)
    {
        $exception = $event->getAuthenticator();
        $this->logger->alert("Authentification fail");
    }


  
}
