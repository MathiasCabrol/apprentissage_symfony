<?php

namespace App\Entity;

use App\Traits\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\HobbyRepository;

#[ORM\Entity(repositoryClass: HobbyRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Hobby
{
    
    use TimestampTrait;
    

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 70)]
    private $designation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function __toString(): string
    {
        return $this->designation;
    }
}
