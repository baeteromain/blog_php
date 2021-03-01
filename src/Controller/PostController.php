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

    private $postManager;

    private $userManager;

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

        $user = $this->session->get('user');

        $categories = $this->categoryManager->getCategories();

        if (!empty($this->post)) {
            $errors = $this->validator->validate($this->post, 'Post');

            if (!empty($_FILES['file_upload']['tmp_name'])) {
                $success_upload = 1;

                $output_dir = './uploads'; //Path for file upload
                $RandomNum = time();
                $file_name = str_replace(' ', '-', strtolower($_FILES['file_upload']['name']));

                $ImageExt = substr($file_name, strrpos($file_name, '.'));
                $ImageExt = str_replace('.', '', $ImageExt);
                $file_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file_name);
                $NewImageName = $file_name.'-'.$RandomNum.'.'.$ImageExt;
                $ret[$NewImageName] = $output_dir.$NewImageName;

                if ($_FILES['file_upload']['size'] > 500000) {
                    $errors['upload'] = "La taille de l'image ne doit pas dépasser 5 MO";
                    $success_upload = 0;
                }

                if ('jpg' != $ImageExt && 'png' != $ImageExt && 'jpeg' != $ImageExt) {
                    $errors['upload'] = 'Seulement les fichiers jpg, jpeg, png sont autorisés';
                    $success_upload = 0;
                }

                if (0 == $success_upload) {
                    $errors['upload'] = "Aucune image n'a été ajoutée";
                }

                $data = [
                    'image' => $NewImageName,
                ];
            } else {
                $data = [
                    'image' => self::BASEIMAGEPOST,
                ];
            }

            if (!$errors) {
                move_uploaded_file($_FILES['file_upload']['tmp_name'], $output_dir.'/'.$NewImageName);

                $this->postManager->createPost($this->post['title'], $this->post['slug'], $data['image'], $this->post['chapo'], $this->post['content'], $user['id']);
                if (isset($this->post['category'])) {
                    $this->postManager->addCategoryToPost($this->post['category'], $this->post['title']);
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
}
