<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/filter", name="category_filter")
     */
    public function filter(): Response
    {
        return $this->render('category/filter.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    /**
     * @Route("/category/no-filter", name="category_no_filter")
     */
    public function noFilter(): Response
    {
        return $this->render('category/no-filter.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
}