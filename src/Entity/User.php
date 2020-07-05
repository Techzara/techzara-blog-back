<?php
/**
 * @Author <julienrajerison5@gmail.com>
 *
 * This file is part of techzara blog
 */
declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *     forceEager=false,
 *     collectionOperations={
 *       "get","post"
 *     },
 *     itemOperations={
 *       "get",
 *       "put" = {"security"="is_granted('ROLE_ADMIN') or object == user"}
 *     },
 *     normalizationContext={"groups"={"user:read"},"enable_max_depth"=true},
 *     denormalizationContext={"groups"={"user:write"},"enable_max_depth"=true}
 * )
 * @ApiFilter(SearchFilter::class, properties={"username":"exact"})
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 *
 * @UniqueEntity(
 *     fields={"username"},
 *     errorPath="username",
 *     message="Username already taken !!!."
 * )
 */
class User implements UserInterface
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @ApiProperty(identifier=false)
     */
    private ?int $id;

    /**
     * The internal primary identity key.
     *
     * @var UuidInterface
     *
     * @ORM\Column(type="uuid", unique=true)
     *
     * @ApiProperty(identifier=true)
     *
     * @Groups("user:read")
     */
    private ?UuidInterface $uuid;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     *
     * @Assert\NotBlank()
     *
     * @Groups({"user:read", "user:write"})
     */
    private string $username;

    /**
     * @ORM\Column(type="string", length=150)
     *
     * @Assert\Email()
     *
     * @Groups({"user:read", "user:write"})
     */
    private string $email;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     *
     * @Groups({"user:read", "user:write"})
     */
    private ?string $pseudo;

    /**
     * @ORM\Column(type="json")
     *
     * @Groups({"user:read"})
     */
    private array $roles;

    /**
     * @ORM\OneToMany(targetEntity=Blog::class, mappedBy="user")
     *
     * @ApiSubresource()
     */
    private ?Collection $blogs;

    /**
     * @ORM\OneToMany(targetEntity=Reaction::class, mappedBy="user")
     */
    private ?Collection $reactions;

    /**
     * @var string|null
     *
     * @SerializedName("password")
     *
     * @Assert\NotBlank()
     *
     * @Groups("user:write")
     */
    private ?string $plainPassword;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user")
     */
    private ?Collection $comments;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->blogs = new ArrayCollection();
        $this->reactions = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return UuidInterface
     */
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    /**
     * @param string|null $pseudo
     *
     * @return $this
     */
    public function setPseudo(?string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     *
     * @return User
     */
    public function setRoles(?array $roles): User
    {
        $this->roles = !empty($roles) ? $roles : ['ROLE_USER'];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     *
     * @return User
     */
    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        return $this->setPlainPassword(null);
    }

    /**
     * @return Collection|Blog[]
     */
    public function getBlogs(): Collection
    {
        return $this->blogs;
    }

    /**
     * @param Blog $blog
     *
     * @return $this
     */
    public function addBlog(Blog $blog): self
    {
        if (!$this->blogs->contains($blog)) {
            $this->blogs[] = $blog;
            $blog->setUser($this);
        }

        return $this;
    }

    /**
     * @param Blog $blog
     *
     * @return $this
     */
    public function removeBlog(Blog $blog): self
    {
        if ($this->blogs->contains($blog)) {
            $this->blogs->removeElement($blog);
            // set the owning side to null (unless already changed)
            if ($blog->getUser() === $this) {
                $blog->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reaction[]
     */
    public function getReactions(): Collection
    {
        return $this->reactions;
    }

    /**
     * @param Reaction $reaction
     *
     * @return $this
     */
    public function addReaction(Reaction $reaction): self
    {
        if (!$this->reactions->contains($reaction)) {
            $this->reactions[] = $reaction;
            $reaction->setUser($this);
        }

        return $this;
    }

    /**
     * @param Reaction $reaction
     *
     * @return $this
     */
    public function removeReaction(Reaction $reaction): self
    {
        if ($this->reactions->contains($reaction)) {
            $this->reactions->removeElement($reaction);
            // set the owning side to null (unless already changed)
            if ($reaction->getUser() === $this) {
                $reaction->setUser(null);
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

    /**
     * @param Comment $comment
     *
     * @return $this
     */
    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    /**
     * @param Comment $comment
     *
     * @return $this
     */
    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     *
     * @Groups("user:read")
     *
     * @SerializedName("createdAt")
     */
    public function getCreatedAt()
    {
        return date_format($this->createdAt,'d-m-Y H:m');
    }


    /**
     * @return string
     *
     * @Groups("user:read")
     *
     * @SerializedName("updatedAt")
     */
    public function getUpdatedAt()
    {
        return date_format($this->updatedAt,'d-m-Y H:m');
    }
}
