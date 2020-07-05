<?php
/**
 * @Author <julienrajerison5@gmail.com>
 *
 * This file is part of techzara blog
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CertificateRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     forceEager=false,
 *      collectionOperations={
 *          "get",
 *          "post"={"security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *          "get",
 *          "put"={"security"="is_granted('ROLE_ADMIN') or object.user == user"},
 *          "delete"={"security"="is_granted('ROLE_ADMIN') or object.user == user"}
 *     },
 *     normalizationContext={"groups"={"certificate:read"},"enable_max_depth"=true},
 *     denormalizationContext={"groups"={"certificate:write"},"enable_max_depth"=true}
 * )
 *
 * @ORM\Entity(repositoryClass=CertificateRepository::class)
 */
class Certificate
{
    use TimestampableEntity;

    public const CERTIFICATE_MENTION = [
        2 => 'Passable',
        3 => 'Good',
        4 => 'Very Good',
        5 => 'Excellent',
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @ApiProperty(identifier=false)
     *
     * @Groups({"certificate:read"})
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
     * @Groups({"certificate:read"})
     */
    private ?UuidInterface $uuid;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     *
     * @Groups({"certificate:read","certificate:write"})
     */
    private ?string $fullName;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank()
     *
     * @Groups({"certificate:read","certificate:write"})
     */
    private ?int $mention;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     *
     * @Assert\NotBlank()
     *
     * @Groups({"certificate:read","certificate:write"})
     */
    private ?string $challenge;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     *
     * @Assert\NotBlank()
     *
     * @var string|null
     *
     * @Groups({"certificate:read","certificate:write"})
     */
    private ?string $type;

    /**
     * @var string|null
     *
     * @Groups({"certificate:read"})
     */
    private ?string $certificateId;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=150, nullable=true)
     *
     * @Groups("certificate:read")
     */
    private ?string $pseudo;

    /**
     * Certificate constructor.
     */
    public function __construct()
    {
        $this->id = null;
        $this->uuid = Uuid::uuid4();
        $this->mention = 2;
        $this->fullName = 'Rakoto';
        $this->type = 'Dev';
        $this->challenge = 'Hackathon';
        $this->pseudo = '';
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
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     *
     * @return $this
     */
    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMention(): ?string
    {
        return self::CERTIFICATE_MENTION[$this->mention];
    }

    /**
     * @return int|null
     */
    public function getMentionToInt(): ?int
    {
        return $this->mention;
    }

    /**
     * @param int $mention
     *
     * @return $this
     */
    public function setMention(int $mention): self
    {
        $this->mention = $mention;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getChallenge(): ?string
    {
        return $this->challenge;
    }

    /**
     * @param string|null $challenge
     *
     * @return $this
     */
    public function setChallenge(?string $challenge): self
    {
        $this->challenge = $challenge;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     *
     * @return Certificate
     */
    public function setType(?string $type): Certificate
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCertificateId(): ?string
    {
        return date_format($this->createdAt, 'Y').'TZ'.$this->id;
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
     * @return Certificate
     */
    public function setPseudo(?string $pseudo): Certificate
    {
        $this->pseudo = $pseudo;

        return $this;
    }
}
