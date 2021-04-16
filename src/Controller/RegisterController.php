<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{

    /**
    * @var EntityManagerInterface $manager
    */
    private $manager;

    /**
     * Passe l'entité manager dans le constructeur afin de le redistribué au sein de la classe
     *
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }

    /**
    * Inscription avec formulaire et redirection vers la page de connexion
    *
    * @param Request $request
    * @param UserPasswordEncoderInterface $encoder Permet de crypter les mot de passes
    * @return Response 
    *
    * @Route("/inscription", name="register")
    */
    public function index(Request $request, UserPasswordEncoderInterface $encoder ): Response
    {

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            $password = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($password);

            $this->manager->persist($user);
            $this->manager->flush();

            $this->addFlash('success', "Bienvenue : " . $user->getUsername());
            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
