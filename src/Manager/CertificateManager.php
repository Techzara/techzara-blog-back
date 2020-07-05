<?php
/**
 * @Author <julienrajerison5@gmail.com>
 *
 * This file is part of techzara blog
 */


namespace App\Manager;

use App\Entity\Certificate;
use App\Repository\CertificateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class CertificateManager.
 */
class CertificateManager
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $em;

    private CertificateRepository $repository;

    /**
     * CertificateManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param CertificateRepository  $repository
     */
    public function __construct(EntityManagerInterface $entityManager, CertificateRepository $repository)
    {
        $this->em = $entityManager;
        $this->repository = $repository;
    }

    /**
     * importation
     */
    public function loadCertificate()
    {
        $data = Yaml::parseFile(__DIR__.'/certificate.yaml');

        foreach ($data as $key => $value) {
            $certificate = $this->repository->findOneBy(['pseudo' => $key]) ?? new Certificate();
            $certificate
                ->setPseudo($key)
                ->setFullName($value['name'])
                ->setChallenge($value['challenge'])
                ->setType($value['type'])
                ->setMention($value['mention']);

            if (!$certificate->getId()) {
                $this->em->persist($certificate);
            }

            $this->em->flush();
        }
    }
}
