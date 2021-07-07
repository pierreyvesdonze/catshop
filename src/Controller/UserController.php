<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\DeliveryAddress;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/register/new", name="new", methods={"GET","POST"})
     */
    public function new(
        Request $request,
        RoleRepository $roleRepository,
        UserPasswordHasherInterface $encoder
    ): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('password')->getData();
            $encodedPassword = $encoder->hashPassword($user, $plainPassword);
            $user->setPassword((string)$encodedPassword);

            $role = $roleRepository->findOneByRoleString('ROLE_USER');
            $user->setRole($role);

            // Associate a cart to new User
            $cart = new Cart;
            $cart->setIsValid(false);
            $user->addCart($cart);

            // Create delivery address
            $deliveryAddress = new DeliveryAddress;
            $deliveryAddress->setFirstName($form->get('firstName')->getData());
            $deliveryAddress->setLastName($form->get('lastName')->getData());
            $deliveryAddress->setNumberStreet($form->get('numberStreetAddress')->getData());
            $deliveryAddress->setStreetName($form->get('streetAddress')->getData());
            $deliveryAddress->setPostalCode($form->get('postalCode')->getData());
            $deliveryAddress->setTown($form->get('town')->getData());
            $deliveryAddress->setAddressTitle($form->get('addressTitle')->getData());

            
            $this->em->persist($user);
            $this->em->persist($cart);
            $this->em->persist($deliveryAddress);
            $this->em->flush();

            $this->addFlash('success', 'Welcome' . $user->getUsername() . ', thanks for registration. You can loggin now !');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/myprofile/{id}", name="user_profile", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function myProfile(User $user): Response
    {      
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/edit", name="user_edit_profile", methods={"GET","POST"})
     */
    public function edit(
        Request $request,
        UserPasswordEncoderInterface $encoder
        ): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('password')->getData();
            $encodedPassword = $encoder->encodePassword($user, $plainPassword);

            $user->setPassword($encodedPassword);

            $this->em->flush();

            $this->addFlash('success', 'Vos informations ont bien été modifiées');

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {

            $this->em->remove($user);
            $this->em->flush();
        }

        $this->addFlash('success', 'Votre compte a bien été supprimé');

        return $this->redirectToRoute('witch_home');
    }
}
