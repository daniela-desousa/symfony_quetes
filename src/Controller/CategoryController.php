<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CategoryType;


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
     * The controller for the category add form
     * Display the form or deal with it
     *
     * @Route("categories/new", name="new")
     */
    public function new(Request $request) : Response
    {
        // Create a new Category Object
        $category = new Category();
        // Create the associated Form
        $form = $this->createForm(CategoryType::class, $category);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted()) {
                   // Deal with the submitted data
                    // Get the Entity Manager
                    $entityManager = $this->getDoctrine()->getManager();
                    // Persist Category Object
                    $entityManager->persist($category);
                    // Flush the persisted object
                    $entityManager->flush();
                    // Finally redirect to categories list
                    return $this->redirectToRoute('category__index');
                }
        // Render the form
        return $this->render('category/new.html.twig', ["form" => $form->createView()]);
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
    