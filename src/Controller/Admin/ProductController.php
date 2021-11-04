<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
  /**
   * @Route("/admin/products", name="adminProductList")
   */
  public function productList(ProductRepository $productRepository)
  {
    $products = $productRepository->findAll();
    return $this->render('admin/products.html.twig', ['products' => $products]);
  }

  /**
   * @Route("/admin/product/{id}", name="adminProductShow")
   */
  public function productShow($id, ProductRepository $productRepository)
  {
    $product = $productRepository->find($id);
    return $this->render('admin/product.html.twig', ['product' => $product]);
  }


  /**
   * @Route("/admin/create/product", name="adminProductCreate")
   */
  public function adminProductCreate(Request $request, EntityManagerInterface $entityManagerInterface)
  {
    $product = new Product();
    $productForm = $this->createForm(ProductType::class, $product);
    $productForm->handleRequest($request);

    if ($productForm->isSubmitted() && $productForm->isValid()) {
      $entityManagerInterface->persist($product);
      $entityManagerInterface->flush();

      return $this->redirectToRoute("adminProductList");
    }
    return $this->render('admin/productForm.html.twig', ['productForm' => $productForm->createView()]);
  }

  /**
   * @Route("/admin/update/product/{id}", name="adminProductUpdate")
   */
  public function adminProductUpdate($id, Request $request, EntityManagerInterface $entityManagerInterface, ProductRepository $productRepository)
  {
    $product = $productRepository->find($id);
    $productForm = $this->createForm(ProductType::class, $product);
    $productForm->handleRequest($request);

    if ($productForm->isSubmitted() && $productForm->isValid()) {
      $entityManagerInterface->persist($product);
      $entityManagerInterface->flush();

      return $this->redirectToRoute("adminProductList");
    }
    return $this->render('admin/productForm.html.twig', ['productForm' => $productForm->createView()]);
  }

  /**
   * @Route("/admin/delete/product/{id}", name="adminProductDelete")
   */
  public function adminProductDelete($id, EntityManagerInterface $entityManagerInterface, ProductRepository $productRepository)
  {
    $product = $productRepository->find($id);
    $entityManagerInterface->remove($product);
    $entityManagerInterface->flush();

    return $this->redirectToRoute('adminProductList');
  }
}
