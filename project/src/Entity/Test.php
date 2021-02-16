<?php

namespace App\Entity;

use App\Repository\TestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TestRepository::class)
 */
class Test
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Test;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTest(): ?string
    {
        return $this->Test;
    }

    public function setTest(string $Test): self
    {
        $this->Test = $Test;

        return $this;
    }
}
