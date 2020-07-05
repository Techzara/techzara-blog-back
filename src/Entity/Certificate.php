<?php
/**
 * @Author <julienrajerison5@gmail.com>
 *
 * This file is part of techzara blog
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Repository\CertificateRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CertificateRepository::class)
 */
class Certificate
{
    private const CERTIFICATE_MENTION = [
        2 => 'Passable',
        3 => 'Bien',
        4 => 'Très Bien',
        5 => 'Excellent',
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
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
     * @ORM\Column(type="string", length=255)
     */
    private string $fullName;

    /**
     * @ORM\Column(type="integer")
     */
    private int $mention;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private string $challenge;

    /**
     * Certificate constructor.
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
}
