<?php
/**
 * @Author <julienrajerison5@gmail.com>
 *
 * This file is part of techzara blog
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CertificateController.
 */
class CertificateController extends AbstractController
{
    /**
     * @Route("/techzara/certificate", name="render_certificate", methods={"GET", "POST"})
     */
    public function renderCertificate()
    {
        return $this->render('certificate/_certificate.html.twig');
    }
}
