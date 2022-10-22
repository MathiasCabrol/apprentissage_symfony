<?php
namespace App\Service;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Security;

class Helpers
{

    public function __construct(private LoggerInterface $logger, Security $security){}

    public function sayCc()
    {
        return 'coucou';
    }

    public function getUser(): User
    {
        return $this->security->getUser();
    }
}