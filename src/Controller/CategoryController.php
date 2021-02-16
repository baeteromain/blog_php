<?php

namespace App\Controller;

use App\core\Controller;
use App\core\Validation\Validation;
use App\Manager\CategoryManager;

class CategoryController extends Controller
{
    private $categoryManager;

    public function __construct()
    {
        parent::__construct();

        $this->categoryManager = new CategoryManager();
        $this->validator = new Validation();
    }

    public function index()
    {
        $this->checkAdmin();

        $categories = $this->categoryManager->getCategories();

        return $this->render('admin/admin_categories/index.twig', [
            'categories' => $categories,
        ]);
    }

    public function addCategory()
    {
        $this->checkAdmin();

        if (!empty($this->get) && isset($this->get['name'], $this->get['slug'])) {
            $errors = $this->validator->validate($this->get, 'Category');

            if (!$errors) {
                $this->categoryManager->createCategory($this->get['name'], $this->get['slug']);

                $this->session->set('add_category', 'La catégorie a bien été ajoutée');

                header('Location: /admin/categories');

                exit();
            }
        }

        return $this->render('admin/admin_categories/add_category.twig', [
            'errors' => $errors ?? null,
            'get' => $this->get ?? null,
        ]);
    }

    public function updateCategory()
    {
        $this->checkAdmin();

        $category = $this->categoryManager->getCategoryById($this->get['id']);

        if (!empty($this->get) && isset($this->get['name'], $this->get['slug'])) {
            $errors = $this->validator->validate($this->get, 'Category');
            if ($category->getName() === $this->get['name']) {
                unset($errors['name']);
            }

            if ($category->getSlug() === $this->get['slug']) {
                unset($errors['slug']);
            }
            if (!$errors) {
                $this->categoryManager->updateCategory($category->getId(), $this->get['name'], $this->get['slug']);

                $this->session->set('update_category', 'La catégorie a bien été modifiée');

                header('Location: /admin/categories');

                exit();
            }
        }

        return $this->render('admin/admin_categories/update_category.twig', [
            'errors' => $errors ?? null,
            'category' => $category ?? null,
        ]);
    }
}
