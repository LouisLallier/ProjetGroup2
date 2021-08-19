<?php

namespace App\Controller;

use App\Entity\Pro;
use App\Entity\User;
use App\Form\ProType;
use App\Form\RegistrationFormType;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{


    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPro(0);
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();



            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/registrationPro",name="registrationPro")
     */
    public function registrationPro(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder,
    UserRepository $userRepository)
    {

        $pro = new Pro();

        $form = $this->createForm(ProType::class, $pro);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()):

            $user=$this->getUser();
            $user->setPro(1);
            $user->setRoles(['ROLE_PRO']);

            $manager->persist($pro);
            $manager->flush();
            $this->addFlash('success', 'Félicitation! votre inscription s\'est bien déroulée. Connectez vous à présent');

            return $this->redirectToRoute('home');
        endif;


        return $this->render('security/registrationPro.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login ", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
//         if ($this->getUser()) {
//             return $this->redirectToRoute('profileUser');
//         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}



