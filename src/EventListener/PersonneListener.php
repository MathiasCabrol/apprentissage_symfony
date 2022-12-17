<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use App\Event\AddPersonneEvent;
use App\Event\ByPagePersonneEvent;

class PersonneListener
{
    public function __construct(
        private LoggerInterface $logger
    )
    {
        
    }
    public function onPersonneAdd(AddPersonneEvent $event)
    {
        $this->logger->debug('cc j\'écoutes l\'évènement personne.add et la personne qui a été ajoutée est '.$event->getPersonne()->getName());
    }

    public function onByPagePersonne(ByPagePersonneEvent $event)
    {
        $this->logger->debug('Le nombre de personnes de la base de données est : '.$event->getNbPersonne());
    }
}