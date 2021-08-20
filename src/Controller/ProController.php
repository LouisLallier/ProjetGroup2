<?php

namespace App\Controller;

use App\Entity\Pro;
use App\Form\ProType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProController extends AbstractController
{
    /**
     * @Route("/one_pro/{id}", name ="one_pro")
     */
    public function onePro($id){
        $pro = $this->getDoctrine()->getRepository(Pro::class)->find($id);


        return $this->render('pro/onePro.html.twig', [
            "pro" => $pro
        ]);
    }

    /**
     * @Route("/update_pro/{id}", name="update_pro")
     */
    public function updatePro($id, Request $request, EntitymanagerInterface $manager)
    {


        $pro =  $this->getDoctrine()->getRepository(Pro::class)->find($id);

        $form = $this->createForm(ProType::class, $pro);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($pro);
            $manager->flush();

            return $this->redirectToRoute('one_pro', [
                'id'=> $id
            ]);

        }

        return $this->render('security/registrationPro.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/delete_pro{id}", name="delete_pro")
     */
    public function suppPro($id, EntitymanagerInterface $manager)
    {


        $pro =  $this->getDoctrine()->getRepository(Pro::class)->find($id);

        $manager->remove($pro);

        $manager->flush();

        return $this->redirectToRoute('registrationPro');

    }


}

