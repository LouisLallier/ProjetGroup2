<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(ServiceRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findAll();


        return $this->render('home/home.html.twig', [
                'services'=>$services
        ]);
    }

}
