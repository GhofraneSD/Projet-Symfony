<?php

namespace App\Entity;

use App\Repository\TalkRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TalkRepository::class)
 */
class Talk extends Filable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $textMessage;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="talks_send")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sender;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="talks_received")
     * @ORM\JoinColumn(nullable=false)
     */
    private $receiver;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sendDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $seen =false;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getTextMessage(): ?string
    {
        return $this->textMessage;
    }

    public function setTextMessage(string $textMessage): self
    {
        $this->textMessage = $textMessage;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getSendDate(): ?\DateTimeInterface
    {
        return $this->sendDate;
    }


    /**
     * Gets triggered only on insert
     * @ORM\PrePersist
     */
    public function setSendDate(\DateTimeInterface $sendDate): self
    {
        $this->sendDate = $sendDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * @param mixed $seen
     */
    public function setSeen($seen): void
    {
        $this->seen = $seen;
    }



}
