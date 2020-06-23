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
use App\Repository\ReactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get","post"
 *     },
 *     itemOperations={
 *          "get",
 *          "delete"={"security"="is_granted('ROLE_ADMIN') or object.user == user"},
 *          "put"={"security"="is_granted('ROLE_ADMIN') or object.user == user"}
 *     },
 *     normalizationContext={"groups"={"reaction:read"}},
 *     denormalizationContext={"groups"={"reaction:write"}}
 * )
 *
 * @ORM\Entity(repositoryClass=ReactionRepository::class)
 */
class Reaction
{
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
     * @Groups("reaction:read")
     */
    private ?UuidInterface $uuid;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     * @Groups({"reaction:read","reaction:write"})
     */
    private ?string $reaction;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reactions")
     *
     * @Groups({"reaction:read","reaction:write"})
     */
    private ?User $user;

    /**
     * @ORM\ManyToOne(targetEntity=Comment::class, inversedBy="reactions")
     *
     * @Groups({"reaction:read","reaction:write"})
     */
    private ?Comment $comment;

    /**
     * @ORM\ManyToOne(targetEntity=Blog::class, inversedBy="reactions")
     *
     * @Groups({"reaction:read","reaction:write"})
     */
    private ?Blog $blog;

    /**
     * Reaction constructor.
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return UuidInterface|null
     */
    public function getUuid(): ?UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @return string|null
     */
    public function getReaction(): ?string
    {
        return $this->reaction;
    }

    /**
     * @param string|null $reaction
     *
     * @return $this
     */
    public function setReaction(?string $reaction): self
    {
        $this->reaction = $reaction;

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
     * @return Comment|null
     */
    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    /**
     * @param Comment|null $comment
     *
     * @return $this
     */
    public function setComment(?Comment $comment): self
    {
        $this->comment = $comment;

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
