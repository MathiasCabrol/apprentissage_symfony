<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ByPagePersonneEvent extends Event
{

    const BY_PAGE_PERSONNE_EVENT = 'personne.by_page';

    public function __construct(private $nbPersonne) {}

    public function getNbPersonne(): int {
        return $this->nbPersonne;
    }
}