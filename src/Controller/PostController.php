<?php

namespace App\Controller;

use App\core\Controller;
use App\core\Validation\Validation;
use App\Manager\CategoryManager;
use App\Manager\PostManager;
use App\Manager\UserManager;

class PostController extends Controller
{
    const BASEIMAGEPOST = 'baseimagepost.jpg';
    const OUTPUT_DIR = './uploads';
    const IMAGE_EXT = ['jpg', 'png', 'jpeg'];

    /**
     * @var PostManager
     */
    private $postManager;
    /**
     * @var CategoryManager
     */
    private $categoryManager;
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var Validation
     */
    private $validator;


    public function __construct()
    {
        parent::__construct();

        $this->postManager = new PostManager();
        $this->categoryManager = new CategoryManager();
        $this->userManager = new UserManager();
        $this->validator = new Validation();
    }

    public function index()
    {
        $this->checkAdmin();

        $posts = $this->postManager->getPosts();

        return $this->render('admin/admin_posts/index.twig', [
            'posts' => $posts,
        ]);
    }

    public function addPost()
    {
        $this->checkAdmin();

        $categories = $this->categoryManager->getCategories();

        if (!empty($this->post)) {
            $user = $this->session->get('user');

            $errors = $this->validator->validate($this->post, 'Post');

            if (!empty($this->files['file_upload']['tmp_name'])) {
                $uploadfile = $this->uploadfile($this->files['file_upload'], self::OUTPUT_DIR);
                $data = $uploadfile;
            } else {
                $data = [
                    'image' => self::BASEIMAGEPOST,
                ];
            }

            if (!$errors) {
                move_uploaded_file($this->files['file_upload']['tmp_name'], self::OUTPUT_DIR.'/'.$data['image']);

                $this->postManager->createPost($this->post['title'], $this->post['slug'], $data['image'], $this->post['chapo'], $this->post['content'], $user['id']);
                if (isset($this->post['category'])) {
                    foreach ($this->post['category'] as $categoryId) {
                        $this->postManager->addCategoryToPost($categoryId, $this->post['title']);
                    }
                }

                $this->session->set('add_post', "L'article bien été ajouté");

                header('Location: /admin/posts');

                exit();
            }
        }

        return $this->render('admin/admin_posts/add_post.twig', [
            'errors' => $errors ?? null,
            'categories' => $categories ?? null,
            'post' => $this->post ?? null,
        ]);
    }

    public function updatePost()
    {
        $this->checkAdmin();
        if (!empty($this->get['id'])) {
            $post = $this->postManager->getPostById($this->get['id']);

            $categories = $this->categoryManager->getCategories();

            $categoriesOfPost = $this->postManager->getCategoryByPost($this->get['id']);
        }

        if (!empty($this->post)) {
            $post = $this->postManager->getPostById($this->post['id']);
            $categoriesOfPost = $this->postManager->getCategoryByPost($this->post['id']);

            $errors = $this->validator->validate($this->post, 'Post');

            if ($post->getTitle() === $this->post['title']) {
                unset($errors['title']);
            }

            if ($post->getSlug() === $this->post['slug']) {
                unset($errors['slug']);
            }
            if (!empty($this->files['file_upload']['tmp_name'])) {
                if (self::BASEIMAGEPOST === $this->files['file_upload']['name']) {
                    $data = [
                        'image' => self::BASEIMAGEPOST,
                    ];
                } else {
                    $uploadfile = $this->uploadfile($this->files['file_upload'], self::OUTPUT_DIR);
                    $data = $uploadfile;
                }
            } else {
                $data = [
                    'image' => $post->getFilename(),
                ];
            }

            if (!$errors) {
                if ($data['image'] != $post->getFilename()) {
                    move_uploaded_file($this->files['file_upload']['tmp_name'], self::OUTPUT_DIR.'/'.$data['image']);
                }

                foreach ($categoriesOfPost as $categoryOfPost) {
                    $cat[] = $categoryOfPost->getId();
                }

                if (empty($this->post['category'])) {
                    foreach ($cat  as $c) {
                        $this->postManager->deleteCategoryOfPost($c['id'], $post->getId());
                    }
                }

                foreach ($this->post['category'] as $categoryid) {
                    if (!in_array($categoryid, $cat)) {
                        $this->postManager->addCategoryToPost($categoryid, $this->post['title']);
                    }

                    $categoriesRemoved = array_diff($cat, $this->post['category']);
                    foreach ($categoriesRemoved as $categoryRemoved) {
                        $this->postManager->deleteCategoryOfPost($categoryRemoved, $post->getId());
                    }
                }

                $this->postManager->updatePost($post->getId(), $this->post['title'], $this->post['slug'], $data['image'], $this->post['chapo'], $this->post['content'], $post->getCreated_at(), 85);

                $this->session->set('update_post', "L'article bien été modifié");

                header('Location: /admin/posts');

                exit();
            }
        }

        return $this->render('admin/admin_posts/update_post.twig', [
            'errors' => $errors ?? null,
            'categories' => $categories ?? null,
            'post' => $post ?? null,
            'get' => $this->get ?? null,
            'categoriesOfPost' => $categoriesOfPost ?? null,
        ]);
    }

    public function deletePost()
    {
        $this->checkAdmin();
        if (!empty($this->get['id'])) {
            $post = $this->postManager->getPostById($this->get['id']);
            $categoriesOfPost = $this->postManager->getCategoryByPost($this->get['id']);

            if ($post) {
                foreach ($categoriesOfPost as $categoryOfPost) {
                    $this->postManager->deleteCategoryOfPost($categoryOfPost->getId(), $post->getId());
                }
                if (self::BASEIMAGEPOST != $post->getFilename()) {
                    unlink(self::OUTPUT_DIR.'/'.$post->getFilename());
                }

                $this->postManager->deletePost($post->getId());

                $this->session->set('delete_post', "L'article a bien été supprimé");

                header('Location: /admin/posts');

                exit();
            }
            $this->session->set('error_delete_post', "Un problème est survenu lors de la suppression de l'article");
        }

        $posts = $this->postManager->getPosts();

        return $this->render('admin/admin_posts/index.twig', [
            'posts' => $posts,
        ]);
    }

    private function uploadfile($file, $output_dir): array
    {
        $success_upload = 1;

        $RandomNum = time();
        $file_name = str_replace(' ', '-', strtolower($file['name']));

        $ImageExt = substr($file_name, strrpos($file_name, '.'));
        $ImageExt = str_replace('.', '', $ImageExt);
        $file_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file_name);
        $NewImageName = $file_name.'-'.$RandomNum.'.'.$ImageExt;
        $ret[$NewImageName] = $output_dir.$NewImageName;

        if ($file['size'] > 500000) {
            $errors['upload'] = "La taille de l'image ne doit pas dépasser 5 MO";
            $success_upload = 0;
        }

        if (!in_array($ImageExt, self::IMAGE_EXT)) {
            $errors['upload'] = 'Seulement les fichiers jpg, jpeg, png sont autorisés';
            $success_upload = 0;
        }

        if (0 == $success_upload) {
            $errors['upload'];
        }

        return [
            'image' => $NewImageName,
        ];
    }
}
