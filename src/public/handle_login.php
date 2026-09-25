<?php
session_start();


function validateLogin(array $data): array
{
    $errors = [];

    $email = $data['email'] ?? '';
    if (strlen($email) == 0) {
        $errors['email'] = "Email обязателен для заполнения";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = "Некорректный формат Email";
    }

    $password = $data['psw'] ?? '';
    if (strlen($password) == 0) {
        $errors['psw'] = "Пароль обязателен для заполнения";
    }

    return $errors;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validateLogin($_POST);

    if (empty($errors)) {
        $email = $_POST['email'] ?? '';
        $password = $_POST['psw'] ?? '';

        $pdo = new PDO('pgsql:host=postgres_db;port=5432;dbname=mydb', 'user', 'pass');
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if (!$user) {
            $errors['email'] = "Email или пароль указаны неверно";
        } else {
            $passwordDb = $user['password'];
            if (password_verify($password, $passwordDb)) {
                $_SESSION['user_id'] = $user['id'];
                header('Location: catalog.php');
                exit;
            } else {
                $errors['email'] = "Email или пароль указаны неверно";
            }
        }
    }
}

require_once './login_form.php';
