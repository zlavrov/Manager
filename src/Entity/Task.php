<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TaskRepository;
use JMS\Serializer\Annotation\Type;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Summary of Task
 */
#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    const STATUS = [
        "new" => 0,
        "in progress" => 1,
        "complete" => 2
    ];

    /**
     * Summary of id
     * @var 
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Summary of title
     * @var 
     */
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * Summary of description
     * @var 
     */
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    /**
     * Summary of status
     * @var 
     */
    #[ORM\Column(type: Types::SMALLINT, options: ["default" => 0])]
    private ?int $status = 0;

    /**
     * Summary of deadline
     * @var 
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $deadline = null;

    /**
     * Summary of createdAt
     * @var 
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * Summary of updatedAt
     * @var 
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * Summary of user
     * @var 
     */
    #[ORM\ManyToOne(inversedBy: 'yes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * Summary of getId
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Summary of getTitle
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Summary of setTitle
     * @param string $title
     * @return \App\Entity\Task
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Summary of getDescription
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Summary of setDescription
     * @param string $description
     * @return \App\Entity\Task
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Summary of getStatus
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * Summary of setStatus
     * @param int $status
     * @return \App\Entity\Task
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Summary of getDeadline
     * @return \DateTimeInterface|null
     */
    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    /**
     * Summary of setDeadline
     * @param \DateTimeInterface $deadline
     * @return \App\Entity\Task
     */
    public function setDeadline(\DateTimeInterface $deadline): self
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Summary of getCreatedAt
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Summary of setCreatedAt
     * @param \DateTimeInterface $createdAt
     * @return \App\Entity\Task
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Summary of getUpdatedAt
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Summary of setUpdatedAt
     * @param \DateTimeInterface|null $updatedAt
     * @return \App\Entity\Task
     */
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Summary of prePersist
     * @return void
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime('now');
    }

    /**
     * Summary of preUpdate
     * @return void
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime('now');
    }

    /**
     * Summary of getUser
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Summary of setUser
     * @param User $user
     * @return \App\Entity\Task
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
