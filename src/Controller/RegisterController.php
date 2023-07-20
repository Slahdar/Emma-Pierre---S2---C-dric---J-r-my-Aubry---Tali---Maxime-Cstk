<?php

namespace App\Controller;

use App\Routing\Attribute\Route;
use App\Models\RegisterModel;
use App\Models\LoginModel;
use Twig\Environment;

class RegisterController extends AbstractController
{
    private RegisterModel $registerModel;
    private LoginModel $loginModel;

    public function __construct(Environment $twig, RegisterModel $registerModel, LoginModel $loginModel)
    {
        parent::__construct($twig);
        $this->registerModel = $registerModel;
        $this->loginModel = $loginModel;
    }

    #[Route("/register", name: "registerPage")]
    public function register(): string
    {
        return $this->twig->render('register.twig');
    }

    #[Route("/register", name: "registerProcess", httpMethods: ['POST'])]
    public function registerProcess(): string
    {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Vérifier si l'utilisateur ou l'adresse e-mail existe déjà
        $userExists = $this->registerModel->checkUserExists($username, $email);

        if ($userExists) {
            $errorMessage = "Nom d'utilisateur ou adresse e-mail déjà utilisé.";
            return $this->twig->render('register.twig', ['register_error' => $errorMessage]);
        }

        // Enregistrer l'utilisateur
        $this->registerModel->registerUser($username, $email, $password);

        $user = $this->loginModel->getUserByUsername($username);
        $this->loginModel->saveUserSession($user['user_id'], $user['statut']);

        // Rediriger l'utilisateur vers une autre page après l'inscription
        header('Location: /');
        exit;
    }
}