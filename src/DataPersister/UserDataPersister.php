<?php
/**
 * @Author <julienrajerison5@gmail.com>
 *
 * This file is part of techzara blog
 */

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserDataPersister.
 */
class UserDataPersister implements DataPersisterInterface
{
    /** @var EntityManagerInterface  */
    private EntityManagerInterface $entityManager;

    /** @var UserPasswordEncoderInterface */
    private UserPasswordEncoderInterface $encoder;

    /**
     * UserDataPersister constructor.
     *
     * @param EntityManagerInterface       $entityManager
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->encoder = $userPasswordEncoder;
    }

    /**
     * @inheritDoc
     */
    public function supports($data): bool
    {
        return $data instanceof User;
    }

    /**
     * @inheritDoc
     */
    public function persist($data)
    {
        if ($data instanceof User && $data->getPlainPassword()) {
            $data->setRoles(['ROLE_USER'])
                ->setPassword($this->encoder->encodePassword($data, $data->getPlainPassword()))
                ->eraseCredentials();
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
