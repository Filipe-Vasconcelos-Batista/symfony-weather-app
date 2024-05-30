<?php

namespace App\Entity;

use App\Repository\SearchEntryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: SearchEntryRepository::class)]
class SearchEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $city_name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $search_time = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCityName(): ?string
    {
        return $this->city_name;
    }

    public function setCityName(string $city_name): static
    {
        $this->city_name = $city_name;

        return $this;
    }

    public function getSearchTime(): ?\DateTimeInterface
    {
        return $this->search_time;
    }

    public function setSearchTime(\DateTimeInterface $search_time): static
    {
        $this->search_time = $search_time;

        return $this;
    }
}
