<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\ServiceRepository;
use App\Repository\SousServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{

    /**
     * @Route("/all_annonces", name="allAnnonces")
     */
    public function allAnnonces(): Response
    {

        $annonces = $this->getDoctrine()->getRepository(Annonce::class)->findAll();


        return $this->render('annonce/allAnnonces.html.twig', [
            "annonces" => $annonces,

        ]);
    }

    /**
     * @Route("/annonce/{id}", name="annonce")
     */

    public function oneAnnonce($id){


        $annonce = $this->getDoctrine()->getRepository(Annonce::class)->find($id);

        return $this->render('annonce/oneAnnonce.html.twig', [
            "annonce" => $annonce,

        ]);
    }




    /**
     * @Route("/add_annonce/{id}", name="add_annonce")
     */
    public function addAnnonce(Request $request, EntityManagerInterface $manager,
                               SousServiceRepository $sousServiceRepository,
                               ServiceRepository $serviceRepository, $id)
    {
        $sousServices = $sousServiceRepository->findBy(['service' => $id]);


        if ($_POST) {

            $sousSer = $sousServiceRepository->find($request->request->get('sousService'));
            //  dd($sousSer);
            $hour = new DateTime($request->request->get('hour'));
            $date = new DateTime($request->request->get('dateAvailable'));

            $annonce = new Annonce();
            $annonce->setPrice($request->request->get('price'));
            $annonce->setCity($request->request->get('city'));
            $annonce->setDateAvailable($date);
            $annonce->setHour($hour);
            $annonce->setDescription($request->request->get('description'));

            $annonce->setDateDeCreation(new DateTime("now"));
            $annonce->setSousService($sousSer, $annonce);
            $manager->persist($annonce);
            $manager->flush();

        }

        return $this->render('annonce/addAnnonces.html.twig', [
            'sousServices'=>$sousServices,



        ]);
    }

    /**
     * @Route("/update_annonce/{id}", name="updateAnnonce")
     */
    public function updateAnnonce($id, Request $request, EntityManagerInterface $manager)
    {

        $annonce = $this->getDoctrine()->getRepository(Annonce::class)->find($id);

        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $annonce->setDateDeModification(new DateTime("now"));
            //le manager est deja déclaréen paramètre de la fonction
            //$manager = $this->getDoctrine()->getManager();

            $manager->persist($annonce);
            $manager->flush();

            return $this->redirectToRoute('allAnnonces');

        }

        return $this->render('annonce/up.html.twig', [
            "formAnnonce" => $form->createView(),
            "annonce" => $annonce
        ]);

    }


    /**
     * @Route("/delete_annonce/{id}", name="deleteAnnonce")
     */
    public function deleteAnnonce ($id, EntityManagerInterface $manager)
    {
        $annonce = $this->getDoctrine()->getRepository(Annonce::class)->find($id);

        $manager->remove($annonce);
        $manager->flush();

        return $this->redirectToRoute('annonces');
    }



}
