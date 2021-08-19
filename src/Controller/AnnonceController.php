<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{

    /**
     * @Route("/allAnnonces", name="allAnnonces")
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
     * @Route("/addAnnonce", name="addAnnonce")
     */
    public function addAnnonce(Request $request, EntityManagerInterface $manager)
    {
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $annonce->setDateDeCreation(new DateTime("now"));

            $manager->persist($annonce);
            $manager->flush();

            return $this->redirectToRoute('annonces');

        }

        return $this->render('annonce/addAnnonce.html.twig', [
            "formAnnonce" => $form->createView(),
            "annonce" => $annonce
        ]);
    }

    /**
     * @Route("/updateAnnonce/{id}", name="updateAnnonce")
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

        return $this->render('annonce/.html.twig', [
            "formAnnonce" => $form->createView(),
            "annonce" => $annonce
        ]);

    }


    /**
     * @Route("/deleteAnnonce/{id}", name="deleteAnnonce")
     */
    public function deleteAnnonce ($id, EntityManagerInterface $manager)
    {
        $annonce = $this->getDoctrine()->getRepository(Annonce::class)->find($id);

        $manager->remove($annonce);
        $manager->flush();

        return $this->redirectToRoute('annonces');
    }



}
