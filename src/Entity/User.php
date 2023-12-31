<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserProfile $userProfile = null;

    #[ORM\ManyToMany(targetEntity: BlogPost::class, mappedBy: 'LikedBy')]
    private Collection $Liked;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: BlogPost::class)]
    private Collection $Blog;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'followers')]
    private Collection $follow;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'follow')]
    private Collection $followers;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private  $confirmationToken;

    #[ORM\Column(type: 'boolean')]
    private bool $confirmed = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $bannedUntill = null;

    public function __construct()
    {
        $this->Liked = new ArrayCollection();
        $this->Blog = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->follow = new ArrayCollection();
        $this->followers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
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

    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserProfile(): ?UserProfile
    {
        return $this->userProfile;
    }

    public function setUserProfile(UserProfile $userProfile): static
    {
        // set the owning side of the relation if necessary
        if ($userProfile->getUser() !== $this) {
            $userProfile->setUser($this);
        }

        $this->userProfile = $userProfile;

        return $this;
    }

    /**
     * @return Collection<int, BlogPost>
     */
    public function getLiked(): Collection
    {
        return $this->Liked;
    }

    public function addLiked(BlogPost $liked): static
    {
        if (!$this->Liked->contains($liked)) {
            $this->Liked->add($liked);
            $liked->addLikedBy($this);
        }

        return $this;
    }

    public function removeLiked(BlogPost $liked): static
    {
        if ($this->Liked->removeElement($liked)) {
            $liked->removeLikedBy($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, BlogPost>
     */
    public function getBlog(): Collection
    {
        return $this->Blog;
    }

    public function addBlog(BlogPost $blog): static
    {
        if (!$this->Blog->contains($blog)) {
            $this->Blog->add($blog);
            $blog->setAuthor($this);
        }

        return $this;
    }

    public function removeBlog(BlogPost $blog): static
    {
        if ($this->Blog->removeElement($blog)) {
            // set the owning side to null (unless already changed)
            if ($blog->getAuthor() === $this) {
                $blog->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
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
     * @return Collection<int, self>
     */
    public function getFollow(): Collection
    {
        return $this->follow;
    }

    public function Follow(self $follow): static
    {
        if (!$this->follow->contains($follow)) {
            $this->follow->add($follow);
        }

        return $this;
    }

    public function unFollow(self $follow): static
    {
        $this->follow->removeElement($follow);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function addFollower(self $follower): static
    {
        if (!$this->followers->contains($follower)) {
            $this->followers->add($follower);
            $follower->Follow($this);
        }

        return $this;
    }

    public function removeFollower(self $follower): static
    {
        if ($this->followers->removeElement($follower)) {
            $follower->unFollow($this);
        }

        return $this;
    }

    public function getBannedUntill(): ?\DateTimeInterface
    {
        return $this->bannedUntill;
    }

    public function setBannedUntill(?\DateTimeInterface $bannedUntill): static
    {
        $this->bannedUntill = $bannedUntill;

        return $this;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken(?string $confirmationToken): void
    {
        $this->confirmationToken = $confirmationToken;
    }


    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    public function setConfirmed(bool $confirmed): void
    {
        $this->confirmed = $confirmed;
    }
}
