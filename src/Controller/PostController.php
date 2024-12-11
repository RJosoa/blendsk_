<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CategoryRepository;

class PostController extends AbstractController
{
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }
    public function list(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        return $this->render('post/list.html.twig', [
            'posts' => $posts, // Doit être "posts"
        ]);
    }

    public function show(int $id, PostRepository $postRepository): Response
    {
        $post = $postRepository->find($id);
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }



    public function create(
        Request $request,
        EntityManagerInterface $em,
        CategoryRepository $categoryRepository
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Récupérer toutes les catégories
        $categories = $categoryRepository->findAll();

        if ($request->isMethod('POST')) {
            $post = new Post();
            $featureImage = $request->request->get('featureImage');
            $title = $request->request->get('title');
            $description = $request->request->get('description');
            $content = $request->request->get('content');
            $categoryId = $request->request->get('category');

            if (!$featureImage || !$title || !$description || !$content) {
                $this->addFlash('error', 'Tous les champs sont requis.');
                return $this->render('post/new.html.twig', ['categories' => $categories]);
            }

            $post->setFeatureImage($featureImage);
            $post->setTitle($title);
            $post->setDescription($description);
            $post->setContent($content);
            $post->setReport(false);

            // Ajouter la catégorie si elle est sélectionnée
            if ($categoryId) {
                $category = $categoryRepository->find($categoryId);
                if ($category) {
                    $post->setCategory($category);
                }
            }

            $em->persist($post);
            $em->flush();

            $this->addFlash('success', 'Post créé avec succès.');
            return $this->redirectToRoute('explorer');
        }

        return $this->render('post/new.html.twig', ['categories' => $categories]);
    }


    public function edit(
        int $id,
        Request $request,
        PostRepository $postRepository,
        EntityManagerInterface $em
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $post = $postRepository->find($id);

        if ($request->isMethod('POST')) {
            $featureImage = $request->request->get('featureImage');
            $title = $request->request->get('title');
            $description = $request->request->get('description');
            $content = $request->request->get('content');

            $post->setFeatureImage($featureImage);
            $post->setTitle($title);
            $post->setDescription($description);
            $post->setContent($content);

            $em->flush();

            $this->addFlash('success', 'Post mis à jour avec succès.');
            return $this->redirectToRoute('explorer');
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
        ]);
    }

    public function delete(int $id, PostRepository $postRepository, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $post = $postRepository->find($id);

        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('explorer');
    }
}
