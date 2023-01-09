<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;

/**
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="type_user", type="string")
 * @DiscriminatorMap({"user" = "User", "doctor" = "Doctor"})
 * @ORM\Table(name="`users`")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;


    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $birth_date;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $profile_pic;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="author", orphanRemoval=true)
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="author", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Talk::class, mappedBy="sender", cascade={"remove"})
     */
    private $talks_send;

    /**
     * @ORM\OneToMany(targetEntity=Talk::class, mappedBy="receiver", cascade={"remove"})
     */
    private $talks_received;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $joined_at;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $gender;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->talks_send = new ArrayCollection();
        $this->talks_received = new ArrayCollection();
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birth_date;
    }

    public function setBirthDate(\DateTimeInterface $birth_date): self
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getProfilePic(): ?string
    {
        return $this->profile_pic;
    }

    public function setProfilePic(?string $profile_pic): self
    {
        $this->profile_pic = $profile_pic;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Talk[]
     */
    public function getTalksSend(): Collection
    {
        return $this->talks_send;
    }

    public function addTalksSend(Talk $talksSend): self
    {
        if (!$this->talks_send->contains($talksSend)) {
            $this->talks_send[] = $talksSend;
            $talksSend->setSender($this);
        }

        return $this;
    }

    public function removeTalksSend(Talk $talksSend): self
    {
        if ($this->talks_send->removeElement($talksSend)) {
            // set the owning side to null (unless already changed)
            if ($talksSend->getSender() === $this) {
                $talksSend->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Talk[]
     */
    public function getTalksReceived(): Collection
    {
        return $this->talks_received;
    }

    public function addTalksReceived(Talk $talksReceived): self
    {
        if (!$this->talks_received->contains($talksReceived)) {
            $this->talks_received[] = $talksReceived;
            $talksReceived->setReceiver($this);
        }

        return $this;
    }

    public function removeTalksReceived(Talk $talksReceived): self
    {
        if ($this->talks_received->removeElement($talksReceived)) {
            // set the owning side to null (unless already changed)
            if ($talksReceived->getReceiver() === $this) {
                $talksReceived->setReceiver(null);
            }
        }

        return $this;
    }

    public function getJoinedAt(): ?\DateTimeImmutable
    {
        return $this->joined_at;
    }

    public function setJoinedAt(\DateTimeImmutable $joined_at): self
    {
        $this->joined_at = $joined_at;

        return $this;
    }

    public function __toString()
    {
        return $this->email;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }
}
