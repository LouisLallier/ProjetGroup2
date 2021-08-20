<?php

namespace App\Controller;

use App\Entity\Prestation;
use App\Form\PrestationType;
use App\Repository\SousServiceRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrestationController extends AbstractController
{
    /**
     * @Route("/prestation/{id}", name="prestation")
     */
    public function onePrestation($id)
    {
        $prestation = $this->getDoctrine()->getRepository(Prestation::class)->find($id);

        return $this->render('prestation/index.html.twig', [
            'controller_name' => 'PrestationController',
        ]);
    }

    /**
     * @Route("/all_prestation", name="allPrestation")
     */
    public function allPrestation(): Response
    {

        $prestations = $this->getDoctrine()->getRepository(Prestation::class)->findAll();


        return $this->render('annonce/allPrestations.html.twig', [
            "prestations" => $prestations,

        ]);
    }
    /**
     * @Route("/add_prestation/{id}", name="add_prestation")
     */
    public function addPrestation(Request $request, EntityManagerInterface $manager, SousServiceRepository $sousServiceRepository, ServiceRepository $serviceRepository, $id, UserRepository $userRepository)
    {
        $sousServices = $sousServiceRepository->findBy(['service' => $id]);


        if ($_POST) {

            $sousSer = $sousServiceRepository->find($request->request->get('sousService'));
            //  dd($sousSer);
            $hour = new DateTime($request->request->get('hour'));
            $date = new DateTime($request->request->get('dateAvailable'));

            $prestation = new Prestation();
            $prestation->setPrice($request->request->get('price'));
            $pro = $this->getPro();
            $prestation->setPro($pro);
            $prestation->setCity($request->request->get('city'));
            $prestation->setDateAvailable($date);
            $prestation->setHour($hour);
            $prestation->setDescription($request->request->get('description'));
            //$prestation->setDispo($request->request->get('dispo'));

           // $prestation->setDateDeCreation(new DateTime("now"));
            $prestation->setSousService($sousSer, $prestation);
            $manager->persist($prestation);
            $manager->flush();

        }

        return $this->render('prestation/addPrestation.html.twig', [
            'sousServices'=>$sousServices,



        ]);
    }
    /**
     * @Route("/update_prestation/{id}", name="updateprestation")
     */
    public function updateprestation($id, Request $request, EntityManagerInterface $manager)
    {

        $prestation = $this->getDoctrine()->getRepository(Prestation::class)->find($id);

        $form = $this->createForm(PrestationType::class, $prestation);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $prestation->setDateDeModification(new DateTime("now"));
            //le manager est deja déclaréen paramètre de la fonction
            //$manager = $this->getDoctrine()->getManager();

            $manager->persist($prestation);
            $manager->flush();

            return $this->redirectToRoute('allPrestations');

        }

        return $this->render('prestation/addPrestations.html.twig', [
            "formPrestation" => $form->createView(),
            "prestation" => $prestation
        ]);

    }


    /**
     * @Route("/delete_prestation/{id}", name="deletePrestation")
     */
    public function deletePrestation ($id, EntityManagerInterface $manager)
    {
        $prestation = $this->getDoctrine()->getRepository(Prestation::class)->find($id);

        $manager->remove($prestation);
        $manager->flush();

        return $this->redirectToRoute('allPrestations');
    }




}
