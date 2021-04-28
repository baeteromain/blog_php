<?php

namespace App\Controller;

use App\core\Controller;
use App\core\Validation\Validation;
use App\Manager\CategoryManager;

class CategoryController extends Controller
{
    /**
     * @var CategoryManager
     */
    private $categoryManager;
    /**
     * @var Validation
     */
    private $validator;

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

        if (!empty($this->getAll) && isset($this->getName, $this->getSlug)) {
            $errors = $this->validator->validate($this->getAll, 'Category');

            if (!$errors) {
                $this->categoryManager->createCategory($this->getName, $this->getSlug);

                $this->session->set('add_category', 'La catégorie a bien été ajoutée');

                header('Location: /admin/categories');

                exit();
            }
        }

        return $this->render('admin/admin_categories/add_category.twig', [
            'errors' => $errors ?? null,
            'get' => $this->getAll ?? null,
        ]);
    }

    public function updateCategory()
    {
        $this->checkAdmin();

        $category = $this->categoryManager->getCategoryById($this->getId);

        if (!empty($this->getAll) && isset($this->getName, $this->getSlug)) {
            $errors = $this->validator->validate($this->getAll, 'Category');
            if ($category->getName() === $this->getName) {
                unset($errors['name']);
            }

            if ($category->getSlug() === $this->getSlug) {
                unset($errors['slug']);
            }
            if (!$errors) {
                $this->categoryManager->updateCategory($category->getId(), $this->getName, $this->getSlug);

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

    public function deleteCategory()
    {
        $this->checkAdmin();

        $category = $this->categoryManager->getCategoryById($this->getId);

        if (!empty($this->getAll) && isset($this->getId) && $category) {
            $this->categoryManager->deleteCategory($this->getId);
            $this->session->set('delete_category', 'La catégorie a bien été supprimée');

            header('Location: /admin/categories');

            exit();
        }

        $this->session->set('error_delete_category', 'Un problème est survenu lors de la suppression de la catégorie');

        $categories = $this->categoryManager->getCategories();

        return $this->render('admin/admin_categories/index.twig', [
            'categories' => $categories,
        ]);
    }
}
