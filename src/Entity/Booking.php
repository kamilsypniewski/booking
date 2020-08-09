<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(type="date")
     */
    private DateTime $startDate;

    /**
     * @ORM\Column(type="date")
     */
    private DateTime $endDate;

    /**
     * @ORM\Column(type="integer")
     */
    private int $price;

    /**
     * @ORM\ManyToMany(targetEntity=Bed::class, inversedBy="bookings")
     */
    private Collection $bed;

    public function __construct()
    {
        $this->bed = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|Bed[]
     */
    public function getBed(): Collection
    {
        return $this->bed;
    }

    public function addBed(Bed $bed): self
    {
        if (!$this->bed->contains($bed)) {
            $this->bed[] = $bed;
        }

        return $this;
    }

    public function removeBed(Bed $bed): self
    {
        if ($this->bed->contains($bed)) {
            $this->bed->removeElement($bed);
        }

        return $this;
    }
}
