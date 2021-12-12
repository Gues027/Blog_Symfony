<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class blogController extends AbstractController
{

    /**
     * @Route("/", name="blog")
     * @param ArticleRepository $articleRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function index(ArticleRepository $articleRepository)
    {
        $article = $articleRepository->findAll();
        return $this->render('blog/index.html.twig', ['articleTableau' => $article]);
    }


    /**
     * @Route("/new", name="new")
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function new(Request $request, EntityManagerInterface $em)
    {
        $article = new Article();
        $user =$this->getUser();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        $article->setAuthor($user->getUsername());  //author linké au user connecter

        if($form->isSubmitted() && $form->isValid()){

            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('blog');
        }
        return $this->renderForm('blog/ajouter.html.twig', [
            'form'=> $form
        ]);
    }


    /**
     * @Route("/edit/{id}", name="edit")
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     * @param Article $article
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function edit(Article $article, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();

            return $this->redirectToRoute('blog');
        }
        return $this->renderForm('blog/ajouter.html.twig', [
            'form'=> $form
        ]);
    }


    /**
     * @Route("/delete/{id}", name="delete")
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     * @param Article $article
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function delete(Article $article, Request $request, EntityManagerInterface $em){
        if($this->isCsrfTokenValid('delete'.$article->getId(), $request->get('_token'))){
            $em->remove($article);
            $em->flush();
        }
        return $this->redirectToRoute('blog');

    }



}