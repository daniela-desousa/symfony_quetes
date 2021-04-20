<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
/**
 * @Route("/categories", name="category__index")
 */
public function index(): Response
{
    $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

    if(!$categories) {
        throw $this->createNotFoundException('Category not found');
    }

    return $this->render('category/index.html.twig', [
        'categories' => $categories,
    ]);
}

/**
     * Getting a categories by name
     *
     * @Route("/categories/{categoryName}", name="category_show")
     * @return Response
     */

    public function show(string  $categoryName): Response
    {
        // $categoryName = $this->getDoctrine()->getRepository(Category::class)->findOneByName($categoryName);
         $categoryName = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['name' =>  $categoryName]);
        if (! $categoryName) {
                   throw $this->createNotFoundException(
                        'No categorie with name : '. $categoryName.' found in category\'s table.'
                    );
                }
        $programs = $this->getDoctrine()->getRepository(Program::class)->findBy(['category' => $categoryName->getId()], ['id' => 'DESC'], 3);


       return $this->render('category/show.html.twig', [
           'programs' => $programs,
           'category_name' =>  $categoryName
       ]);
    }
}
    