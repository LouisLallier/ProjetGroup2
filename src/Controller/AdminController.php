<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\SousService;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Form\ServiceType;
use App\Form\SousServiceType;
use App\Form\UpdateUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/allusers", name="allUsers")
     */
    public function allUsers(): Response
    {
    $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('admin/allUsers.html.twig', [

            "users" => $users
        ]);
    }




//    ---------------------------------------------------------
//    -------------  SERVICE  ---------------------
//    ---------------------------------------------------------


    /**
     * @Route("/allservices", name="allServices")
     */
    public function allService(): Response
    {
        $services = $this->getDoctrine()->getRepository(Service::class)->findAll();

        return $this->render('admin/allServices.html.twig', [
            "services" => $services
        ]);
    }


    /**
    *@Route("/add_service",name="add_service")
    */
    public function addService (Request $request, EntityManagerInterface $manager)
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($service);
            $manager->flush();
            return $this->redirectToRoute('allServices');
        }
        return $this->render('admin/addService.html.twig', [
            'form'=>$form->createView(),
            'service'=> $service
        ]);

    }


    /**
     * @Route("/update_sevice/{id}", name="update_sevice")
     */
    public function updateService($id, Request $request, EntityManagerInterface $manager)
    {

        $service = $this->getDoctrine()->getRepository(Service::class)->find($id);

        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($service);
            $manager->flush();

            return $this->redirectToRoute('add_service');

        }

        return $this->render('admin/addService.html.twig', [
            'form' => $form->createView(),
            'service' => $service
        ]);
    }

    /**
     * @Route("/delete_service/{id}", name="delete_service")
     */
    public function deleteService ($id, EntityManagerInterface $manager)
    {
        $service = $this->getDoctrine()->getRepository(Service::class)->find($id);

        $manager->remove($service);
        $manager->flush();

        return $this->redirectToRoute('allServices');
    }



//    ---------------------------------------------------
//-------------------- SOUS SERVICE ------------------------
//----------------------------------------------------------


    /**
     * @Route("/all_sous_services", name="all_sous_services")
     */
    public function allSousService(): Response
    {
        $sousServices = $this->getDoctrine()->getRepository(SousService::class)->findAll();
        //.dd($sousServices);
        return $this->render('admin/allSousServices.html.twig', [
            "sousServices" => $sousServices
        ]);
    }


    /**
     *@Route("/add_sous_service/{id}",name="add_sous_service")
     */
    public function addSousService (Request $request, EntityManagerInterface $manager)
    {
        $sousService = new SousService();
        $form = $this->createForm(SousServiceType::class, $sousService);
        $form->handleRequest($request);



        if($form->isSubmitted() && $form->isValid()){
            //dd($sousService);
            $manager->persist($sousService);
            $manager->flush();
            return $this->redirectToRoute('all_sous_services');
        }
        return $this->render('admin/addSousServices.html.twig', [
            'form'=>$form->createView(),
            'sousService'=> $sousService
        ]);

    }





}
