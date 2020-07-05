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
use App\Repository\BlogRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Nonstandard\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     forceEager=false,
 *      collectionOperations={
 *          "get",
 *          "post"={"security"="is_granted('ROLE_USER')"}
 *     },
 *     itemOperations={
 *          "get",
 *          "put"={"security"="is_granted('ROLE_ADMIN') or object.user == user"},
 *          "delete"={"security"="is_granted('ROLE_ADMIN') or object.user == user"}
 *     },
 *     normalizationContext={"groups"={"blog:read"},"enable_max_depth"=true},
 *     denormalizationContext={"groups"={"blog:write"},"enable_max_depth"=true}
 * )
 *
 * @ORM\Entity(repositoryClass=BlogRepository::class)
 */
class Blog
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
     * @Groups("blog:read")
     */
    private ?UuidInterface $uuid;

    /**
     * @ORM\Column(type="text")
     *
     * @Groups({"blog:read","blog:write"})
     *
     * @Assert\NotBlank()
     */
    private string $title;

    /**
     * @ORM\Column(type="text")
     *
     * @Groups({"blog:read","blog:write"})
     *
     * @Assert\NotBlank()
     */
    private string $description;

    /**
     * @var Collection|null
     *
     * @ORM\OneToMany(targetEntity=MediaObject::class, cascade={"persist","remove"}, mappedBy="blog")
     *
     * @ApiProperty(iri="http://schema.org/image")
     *
     * @Groups({"blog:read","blog:write"})
     */
    private ?Collection $images;

    /**
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="blogs")
     *
     * @Groups({"blog:read","blog:write"})
     */
    private ?User $user;

    /**
     * @ORM\OneToMany(targetEntity=Tag::class, mappedBy="blog")
     *
     * @Groups({"blog:read","blog:write"})
     */
    private Collection $tags;

    /**
     * @ORM\OneToMany(targetEntity=Reaction::class, mappedBy="blog")
     *
     * @Groups("blog:read")
     *
     * @ApiSubresource()
     */
    private Collection $reactions;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="blog")
     *
     * @Groups("blog:read")
     *
     * @ApiSubresource()
     */
    private ?Collection $comments;

    /**
     * Blog constructor.
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->images = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->reactions = new ArrayCollection();
        $this->comments = new ArrayCollection();
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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getImages(): ?Collection
    {
        return $this->images;
    }

    /**
     * @param MediaObject|null $image
     *
     * @return Blog
     */
    public function addImages(?MediaObject $image): Blog
    {
        $this->images->add($image);

        return $this;
    }

    /**
     * @param MediaObject|null $image
     *
     * @return Blog
     */
    public function removeImages(?MediaObject $image): Blog
    {
        $this->images->removeElement($image);

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
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * @param Tag $tag
     *
     * @return $this
     */
    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->setBlog($this);
        }

        return $this;
    }

    /**
     * @param Tag $tag
     *
     * @return $this
     */
    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            // set the owning side to null (unless already changed)
            if ($tag->getBlog() === $this) {
                $tag->setBlog(null);
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
            $reaction->setBlog($this);
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
            if ($reaction->getBlog() === $this) {
                $reaction->setBlog(null);
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
            $comment->setBlog($this);
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
            if ($comment->getBlog() === $this) {
                $comment->setBlog(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     *
     * @Groups("blog:read")
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
     * @Groups("blog:read")
     *
     * @SerializedName("updatedAt")
     */
    public function getUpdatedAt()
    {
        return date_format($this->updatedAt,'d-m-Y H:m');
    }

    /**
     * @return string
     *
     * @Groups("blog:read")
     *
     * @SerializedName("createBy")
     */
    public function getCreateBy()
    {
        return $this->getUser() ? $this->getUser()->getUsername() : 'Techzara';
    }
}
