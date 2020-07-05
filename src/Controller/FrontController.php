<?php
/**
 * @Author <julienrajerison5@gmail.com>
 *
 * This file is part of techzara blog
 */

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FrontController.
 */
class FrontController extends AbstractController
{
    /**
     * Render the welcome page
     *
     * @Route("/", name="front_rendering")
     *
     * @return Response
     */
    public function welcomePage()
    {
        return $this->render('front/_front_index.html.twig');
    }
}
