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
        if (!empty($this->get) && isset($this->get['name'], $this->get['slug'])) {
            $this->categoryManager->createCategory($this->get['name'], $this->get['slug']);

            header('Location: /admin/categories');

            exit();
        }

        return $this->render('admin/admin_categories/add_category.twig', [
            'errors' => $errors ?? null,
        ]);
    }
}
