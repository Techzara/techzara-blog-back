<?php
/**
 * @Author <julienrajerison5@gmail.com>
 *
 * This file is part of techzara blog
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={
        "get","post"
 *     },
 *     itemOperations={
*       "get","put"
 *     },
 *     normalizationContext={"groups"={"read"}},
 *     denormalizationContext={"groups"={"write"}}
 * )
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read", "write"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=150)
     *
     * @Assert\Email()
     *
     * @Groups({"read", "write"})
     */
    private $email;

    /**
     * @ORM\Column(type="text", nullable=false)
     *
     * @Groups("read")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     *
     * @Groups({"read", "write"})
     */
    private $pseudo;

    /**
     * @ORM\Column(type="json")
     *
     * @Groups({"read", "write"})
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=Blog::class, mappedBy="user")
     *
     * @Groups("read")
     */
    private $blogs;

    /**
     * @ORM\OneToMany(targetEntity=Reaction::class, mappedBy="user")
     */
    private $reactions;

    /**
     * @var string|null
     *
     * @SerializedName("password")
     *
     * @Assert\NotBlank()
     */
    private $plainPassword;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->blogs = new ArrayCollection();
        $this->reactions = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @param string $roles
     *
     * @return User
     */
    public function setRoles(string $roles): User
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // Return salt
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
}
