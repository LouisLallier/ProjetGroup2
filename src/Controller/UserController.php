<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UpdateUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/updateUser/{id}", name="updateUser")
     */
    public function updateUser($id, Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->createForm(UpdateUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($user);
            $manager->flush();

            return $this->render('admin/updateUser.html.twig', [

            ]);
        }


    }

    /**
     * @Route("/deleteUser/{id}", name="deleteUser")
     */
    public function suppUser($id, EntitymanagerInterface $manager)
    {

        $user =  $this->getDoctrine()->getRepository(User::class)->find($id);

        $manager->remove($user);


        $manager->flush();

        return $this->redirectToRoute('allUsers');

    }
    /**
    *@Route("/profileUser/{id}",name="profileUser")
    */
    public function profileUser($id, UserRepository $userRepository){

        $profile = $userRepository->find($id);


        return $this->render('user/profile.html.twig',[
            'profile'=>$profile
        ]);

    }





}
