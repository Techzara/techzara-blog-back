<?php
/**
 * @Author <julienrajerison5@gmail.com>
 *
 * This file is part of techzara blog
 */

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get",
 *          "post"
 *     },
 *     itemOperations={
 *          "get",
 *          "delete"={"security"="is_granted('ROLE_ADMIN') or object.user == user"},
 *          "put"={"security"="is_granted('ROLE_ADMIN') or object.user == user"}
 *     },
 *     normalizationContext={"groups"={"blog:read","comment:read"}},
 *     denormalizationContext={"groups"={"comment:write"}}
 * )
 *
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @ApiProperty(identifier=false)
     */
    private int $id;

    /**
     * The internal primary identity key.
     *
     * @var UuidInterface
     *
     * @ORM\Column(type="uuid", unique=true)
     *
     * @ApiProperty(identifier=true)
     *
     * @Groups("comment:read")
     */
    private ?UuidInterface $uuid;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Groups({"blog:read","comment:read","comment:write"})
     */
    private ?string $comment;

    /**
     * @ORM\OneToMany(targetEntity=Reaction::class, mappedBy="comment")
     *
     * @Groups({"comment:read"})
     *
     * @ApiSubresource()
     */
    private ?Collection $reactions;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     *
     * @Groups({"blog:read","comment:read","comment:write"})
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity=Blog::class, inversedBy="comments")
     *
     * @Groups({"comment:read","comment:write"})
     */
    private Blog $blog;

    /**
     * Comment constructor.
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
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
     * @return UuidInterface
     */
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string|null $comment
     *
     * @return $this
     */
    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

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
            $reaction->setComment($this);
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
            if ($reaction->getComment() === $this) {
                $reaction->setComment(null);
            }
        }

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     *
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Blog|null
     */
    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    /**
     * @param Blog|null $blog
     *
     * @return $this
     */
    public function setBlog(?Blog $blog): self
    {
        $this->blog = $blog;

        return $this;
    }
}
