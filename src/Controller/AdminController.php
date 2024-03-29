<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\SousService;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Form\ServiceType;
use App\Form\SousServiceType;
use App\Form\UpdateUserType;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
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

    /**
     * @Route("/promote_admin/{id}", name="promote_admin")
     */
    public function promoteToAdmin($id, EntityManagerInterface $manager, UserRepository $userRepository)
    {
        $user = $userRepository->find($id);

        if ($user->getRoles() == ["ROLE_USER"]) {
            $user->setRoles(["ROLE_ADMIN"]);
        } elseif($user->getRoles() == ["ROLE_PRO"]) {
            $user->setRoles(["ROLE_ADMIN"]);
        } else {
            $user->setRoles(["ROLE_USER"]);
        }
        $manager->persist($user);
        $manager->flush();
        $this->addFlash('success', 'Bravo !');

        return $this->redirectToRoute('allUsers');
    }

    /**
     * @Route("/promote_pro/{id}", name="promote_pro")
     */
    public function promoteToPro($id, EntityManagerInterface $manager, UserRepository $userRepository)
    {
        $user = $userRepository->find($id);

        if ($user->getRoles() == ["ROLE_USER"]) {
            $user->setRoles(["ROLE_PRO"]);

        } elseif($user->getRoles() == ["ROLE_ADMIN"]) {
            $user->setRoles(["ROLE_PRO"]);

        } else {
            $user->setRoles(["ROLE_USER"]);
        }
        $manager->persist($user);
        $manager->flush();
        $this->addFlash('success', 'Bravo !');

        return $this->redirectToRoute('allUsers');
    }
    /**
    *@Route("/homeAdmin",name="homeAdmin")
    */
    public function homeAdmin(){

        return $this->render('panelAdmin.html.twig',[
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

            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $nomImage = date("YmdHis") . "-" . uniqid() . "-" . $imageFile->getClientOriginalName();

                $imageFile->move(
                    $this->getParameter("image_service"),
                    $nomImage);

                $service->setImage($nomImage);
            }
            $this->addFlash('success', "Le service N° ". $service->getId()." a bien été ajouté");

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

        $serviceid = $service->getId();
        $sousservices = $this->getDoctrine()->getRepository(SousService::class)->findBy(['id'=>$serviceid]);

        if($service->getImage()){
            unlink($this->getParameter("image_service") . "/" . $service->getImage());
        }
            //foreach ($sousservices as $sousservice) {
       // $manager->remove($sousservice);
   // }
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
    public function addSousService (Request $request, EntityManagerInterface $manager, ServiceRepository $repository, $id)
    {




        if($_POST){
            //dd($sousService);
             $sousService = new SousService();
            $prix=$request->request->get('price');
                   $name=$request->request->get('name');$serviceId=$request->request->get('id');
                   $service=$repository->find($serviceId);
            $sousService->setPrice($prix);
            $sousService->setNom($name);
            $sousService->setService($service);
            $manager->persist($sousService);
            $manager->flush();
            return $this->redirectToRoute('all_sous_services');
        }
        return $this->render('admin/addSousServices.html.twig', [
            'id'=>$id,

        ]);

    }





}
