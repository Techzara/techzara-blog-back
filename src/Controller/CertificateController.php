<?php
/**
 * @Author <julienrajerison5@gmail.com>
 *
 * This file is part of techzara blog
 */

namespace App\Controller;

use App\Entity\Certificate;
use App\Form\CertificateType;
use App\Repository\CertificateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CertificateController.
 */
class CertificateController extends AbstractController
{
    /**  @var EntityManagerInterface */
    private EntityManagerInterface $em;

    /** @var CertificateRepository */
    private CertificateRepository $repository;

    /**
     * CertificateController constructor.
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
     * @Route("/techzara/certificate/{uuid}", name="render_certificate", methods={"GET", "POST"})
     *
     * @param string|null $uuid
     *
     * @return Response
     */
    public function renderCertificate(?string $uuid = null)
    {
        try {
            $certificate = $this->repository->findOneBy(['fullName' => $uuid]);

            if (!$certificate){
                $certificate = $this->repository->findOneBy(['pseudo' => $uuid]);
            }

            if (!$certificate) {
                $certificate = $this->repository->findOneBy(['uuid' => $uuid]);
            }

            return $this->render('certificate/_certificate.html.twig', ['certificate' => $certificate]);
        } catch (\Exception $exception) {
            return $this->redirectToRoute('front_rendering');
        }
    }

    /**
     * @Route("/techzara/manage/certificate/new/{id?}", name="create_certficate", methods={"GET","POST"})
     *
     * @param Request          $request
     * @param Certificate|null $certificate
     *
     * @return Response
     */
    public function createCertificate(Request $request, ?Certificate $certificate)
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $certificate = $certificate ?? new Certificate();

            $form = $this->createForm(CertificateType::class, $certificate);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if (!$certificate->getId()) {
                    $this->em->persist($certificate);
                }

                $this->em->flush();

                return $this->redirectToRoute('render_certificate', ['uuid' => $certificate->getUuid()]);
            }

            return $this->render('certificate/_create_certificate.html.twig', ['form' => $form->createView()]);
        }

        return $this->redirectToRoute('front_rendering');
    }
}
