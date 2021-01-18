<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{


	/**
	 * @Route("/users/create", name="user_create")
	 * @param Request $request
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 * @param EntityManagerInterface $entityManager
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function createAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
	{
		$user = new User();
		$form = $this->createForm(UserType::class, $user);

		$form->handleRequest($request);


		if ($form->isSubmitted() && $form->isValid()) {
			$password = $passwordEncoder->encodePassword($user, $user->getPassword());
			$user->setPassword($password);
			$entityManager->persist($user);
			$entityManager->flush();

			$this->addFlash('success', "L'utilisateur a bien été ajouté.");

			return $this->redirectToRoute('homepage');
		}

		return $this->render('user/create.html.twig', ['form' => $form->createView()]);
	}

	/**
	 * @param User $user
	 * @param Request $request
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 * @param EntityManagerInterface $entityManager
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 * @Route("/users/{id}/edit", name="user_edit")
	 * @IsGranted("USER_EDIT", subject="user")
	 */
	public function editUser(
		User $user, Request $request,
		UserPasswordEncoderInterface $passwordEncoder,
		EntityManagerInterface $entityManager
	)
	{
		$form = $this->createForm(UserType::class, $user);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$password = $passwordEncoder->encodePassword($user, $user->getPassword());
			$user->setPassword($password);
			$entityManager->flush();
			$this->addFlash('success', "Vos informations ont bien été modifiées");

			return $this->redirectToRoute('homepage');
		}

		return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
	}

}
