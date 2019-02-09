<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Image;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use Doctrine\Tests\Common\DataFixtures\TestEntity\User;
use Symfony\Component\HttpFoundation\File\File;

use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @Route("/")
 */
class BookController extends AbstractController
{

    private $bookRepository;
    private $userRepository;
    private $categoryRepository;

    public function __construct(UserRepository $userRepository, BookRepository $bookRepository, CategoryRepository $categoryRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->userRepository = $userRepository;
        $this->categoryRepository = $categoryRepository;
    }
    /**
     * @Route("/book", name="book_index", methods="GET|POST")
     */
    public function index(Request $request): Response
    {
        $categories = $this->categoryRepository->findAll();

        $books = $this->bookRepository->findAll();

        return $this->render('book/index.html.twig', [
            'books' => $books,
            'categories' => $categories
            ]);
    }

    /**
     * @Route("/", name="index", methods="GET")
     */
    public function redirectToIndex() : Response
    {
        return $this->redirectToRoute('book_index');
    }

    /**
     * @Route("/book/new", name="book_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            
            $em->persist($book);
            $em->flush();

            $this->addFlash(
                'success',
                'Le livre a bien été ajouté'
            );

            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/book/{id}", name="book_show", methods="GET")
     */
    public function show($id): Response
    {

        $book = $this->bookRepository->find($id);
        return $this->render('book/show.html.twig', [
            'book' => $book,
            'users' => $this->userRepository->findAll()
            ]);
    }

    /**
     * @Route("/book/{id}/edit", name="book_edit", methods="GET|POST")
     */
    public function edit(Request $request, Book $book): Response
    {

        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->persist($book);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'Le modifications ont bien été enregistrées'
            );

            return $this->redirectToRoute('book_show', ['id' => $book->getId()]);
        }


        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
            'categories' => $this->categoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/book/{id}", name="book_delete", methods="DELETE")
     */
    public function delete(Request $request, Book $book): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($book);
 
            $em->flush();


            $this->addFlash(
                'success',
                'Le livre a bien été supprimé'
            );
        }


        return $this->redirectToRoute('book_index');
    }

    

}
