<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * @Route("/", name="user_index", methods="GET")
     */
    public function index() : Response
    {
        return $this->render('user/index.html.twig', ['users' => $this->userRepository->findAll()]);
    }

    /**
     * @Route("/new", name="user_new", methods="GET|POST")
     */
    public function new(Request $request) : Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $randomIdentifiant = $this->randomIdentifiant();

            $user->setIdentifiant($randomIdentifiant);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{identifiant}", name="user_show", methods="GET")
     */
    public function show(User $user) : Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/{identifiant}/edit", name="user_edit", methods="GET|POST")
     */
    public function edit(Request $request, User $user) : Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_show', ['identifiant' => $user->getIdentifiant()]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods="DELETE")
     */
    public function delete(Request $request, User $user) : Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            if ($user->getNbBook() !== 0) {
                $this->addFlash(
                    'error',
                    'L\'utilisateur ne peut pas être supprimé tant qu\'il possède un livre'
                );

                return $this->render('user/show.html.twig', [
                    'user' => $user,
                ]);
            }
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            $this->addFlash(
                'success',
                'L\'utilisateur a bien été supprimé'
            );
        }


        return $this->redirectToRoute('user_index');
    }

    /**
     * Generate random string for identifiant
     *
     * @return string
     */
    public function randomIdentifiant()
    {
        $randomIdentifiant = bin2hex(random_bytes(4));
        if ($this->userRepository->checkIfRandomExists($randomIdentifiant)) {
            $this->randomIdentifiant();
        }
        return $randomIdentifiant;
    }
}
