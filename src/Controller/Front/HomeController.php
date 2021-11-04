<?php

namespace App\Controller\Front;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
  /**
   * @Route("/home", name="home")
   */
  public function home()
  {
    return $this->render('front/home.html.twig');
  }

  /**
   * @Route("/search", name="search")
   */
  public function search(ProductRepository $productRepository, Request $request)
  {
    $term = $request->query->get("search");
    $products = $productRepository->searchByTerm($term);

    return $this->render('front/search.html.twig', ['products' => $products, 'term' => $term]);
  }
}
