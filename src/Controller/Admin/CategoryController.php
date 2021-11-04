<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{
  /**
   * @Route("/admin/categories", name="adminCategoryList")
   */
  public function adminCategoriesList(CategoryRepository $categoryRepository)
  {
    $categories = $categoryRepository->findAll();
    return $this->render('admin/categories.html.twig', ['categories' => $categories]);
  }

  /**
   * @Route("/admin/category/{id}", name="adminCategoryShow")
   */
  public function adminCategoryShow($id, CategoryRepository $categoryRepository)
  {
    $category = $categoryRepository->find($id);
    return $this->render('admin/category.html.twig', ['category' => $category]);
  }

  /**
   * @Route("/admin/create/category", name="adminCategoryCreate")
   */
  public function adminCategoryCreate(EntityManagerInterface $entityManagerInterface, Request $request)
  {
    $category = new Category;
    $categoryForm = $this->createForm(CategoryType::class, $category);
    $categoryForm->handleRequest($request);

    if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
      $entityManagerInterface->persist($category);
      $entityManagerInterface->flush();

      return $this->redirectToRoute('adminCategoryList');
    }
    return $this->render('admin/categoryForm.html.twig', ['categoryForm' => $categoryForm->createView()]);
  }

  /**
   * @Route("/admin/update/category/{id}", name="adminCategoryUpdate")
   */
  public function adminCategoryUpdate($id, Request $request, EntityManagerInterface $entityManagerInterface, CategoryRepository $categoryRepository)
  {
    $category = $categoryRepository->find($id);
    $categoryForm = $this->createForm(CategoryType::class, $category);
    $categoryForm->handleRequest($request);

    if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
      $entityManagerInterface->persist($category);
      $entityManagerInterface->flush();
      return $this->redirectToRoute("adminCategoryList");
    }
    return $this->render("admin/categoryForm.html.twig", ['categoryForm' => $categoryForm->createView()]);
  }

  /**
   * @Route("admin/delete/category/{id}", name="adminCategoryDelete")
   */
  public function adminCategoryDelete($id, EntityManagerInterface $entityManagerInterface, CategoryRepository $categoryRepository)
  {
    $category = $categoryRepository->find($id);
    $entityManagerInterface->remove($category);
    $entityManagerInterface->flush();
    return $this->redirectToRoute('adminCategoryList');
  }
}
