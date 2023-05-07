<?php

namespace App\Controller\Web;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Summary of HomeController
 */
class HomeController extends AbstractController
{
    /**
     * Summary of index
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $user = $this->getUser();
        if(!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('page/home.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
