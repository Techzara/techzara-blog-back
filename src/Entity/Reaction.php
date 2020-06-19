<?php
/**
 * @Author <julienrajerison5@gmail.com>
 *
 * This file is part of techzara blog
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ReactionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ReactionRepository::class)
 */
class Reaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $reaction;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reactions")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Comment::class, inversedBy="reactions")
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=Blog::class, inversedBy="reactions")
     */
    private $blog;

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
