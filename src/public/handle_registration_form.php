<?php

function validateRegistration(array $data): array
{
    $errors = [];

    $name = $data['name'] ?? '';
    if (strlen($name) == 0) {
        $errors['name'] = "Имя обязательно для заполнения";
    } elseif (strlen($name) < 2) {
        $errors['name'] = "Имя должно содержать не менее 2 символов";
    }

    $email = $data['email'] ?? '';
    if (strlen($email) < 3) {
        $errors['email'] = "Email слишком короткий";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = "Некорректный формат Email";
    }

    $password = $data['psw'] ?? '';
    if (strlen($password) < 6) {
        $errors['psw'] = "Пароль должен содержать не менее 6 символов";
    }

    $passwordRepeat = $data['psw-repeat'] ?? '';
    if ($password !== $passwordRepeat) {
        $errors['psw-repeat'] = "Пароли не совпадают";
    }

    return $errors;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validateRegistration($_POST);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
    $pdo = new PDO('pgsql:host=postgres_db;port=5432;dbname=mydb', 'user', 'pass');

    $check = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $check->execute(['email' => $_POST['email'] ?? '']);

    if ($check->fetch()) {
        $errors['email'] = "Пользователь с таким email уже зарегистрирован";
    } else {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['psw'] ?? '';

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute(['name' => $name,'email' => $email, 'password' => $hashedPassword]);

        $newUserId = $pdo->lastInsertId();

        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $newUserId]);
        $data = $stmt->fetch();

        echo "Регистрация успешна!<br>";
        echo "Имя: " . ($data['name']) . "<br>";
        echo "Email: " . ($data['email']) . "<br>";
    }
}

require_once './registration_form.php';
