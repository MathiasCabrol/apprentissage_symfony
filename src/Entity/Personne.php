<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PersonneRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Personne
{

    use TimestampTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 60)]
    #[Assert\NotBlank(message: 'Veuillez renseigner ce champs')]
    #[Assert\Length(min: 4, minMessage: 'Veuillez avoir au moins 4 caractères')]
    private $firstname;

    #[ORM\Column(type: 'string', length: 60)]
    private $name;

    #[ORM\Column(type: 'smallint')]
    private $age;

    #[ORM\OneToOne(inversedBy: 'personne', targetEntity: Profile::class, cascade: ['persist', 'remove'])]
    private $profile;

    #[ORM\ManyToMany(targetEntity: Hobby::class)]
    private $hobbies;

    #[ORM\ManyToOne(targetEntity: Job::class, inversedBy: 'personnes')]
    private $job;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $image;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'personnes')]
    private $createdBy;

    public function __construct()
    {
        $this->hobbies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(null|Profile|ArrayCollection $profile): self
    {
        if(!$profile instanceof Profile) {
            if ($profile instanceof ArrayCollection && !$profile->isEmpty()) {
                $profile = current($profile->toArray());
            }
        }
        $this->profile = $profile;

        return $this;
    }

    /**
     * @return Collection<int, Hobby>
     */
    public function getHobbies(): Collection
    {
        return $this->hobbies;
    }

    public function addHobby(Hobby $hobby): self
    {
        if (!$this->hobbies->contains($hobby)) {
            $this->hobbies[] = $hobby;
        }

        return $this;
    }

    public function removeHobby(Hobby $hobby): self
    {
        $this->hobbies->removeElement($hobby);

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

}
