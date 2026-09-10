<?php

$name = '';
$email = '';
$password = '';
$passwordRepeat = '';

$errors = [];

if (isset($_POST['name'])) {
    $name = $_POST['name'];
    if (strlen($name) == 0) {
        $errors ['name'] = "Имя обязательно для заполнения";
    }elseif(strlen($name) < 2) {
        $errors ['name'] = "Имя должно содержать содержать не менее 2 символов";
    }
} else {
    $errors['name'] = "Поле name отсутствует";
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    if (strlen($email) < 3) {
        $errors ['email'] = "Email слишком короткий";
    }elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = "Некорректный формат Email";
    }
} else {
    $errors['email'] = "Поле email отсутствует";
}

if (isset($_POST['psw'])) {
    $password = $_POST['psw'];

    if (strlen($password) < 6) {
        $errors ['psw'] = "Пароль должен содержать не менее 6 символов";
    }
} else {
    $errors['psw'] = "Поле password отсутствует";
}

if (isset($_POST['psw-repeat'])) {
    $passwordRepeat = $_POST['psw-repeat'];
    if ($password !== $passwordRepeat) {
        $errors ['psw-repeat'] = "Пароли не совпадают";
    }
} else {
    $errors['psw-repeat'] = "Поле Repeat Password отсутствует";
}


if (empty($errors)) {
    $pdo = new PDO('pgsql:host=postgres_db;port=5432;dbname=mydb', 'user', 'pass');

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
    $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);

    $newUserId = $pdo->lastInsertId();

    $statement = $pdo->query("SELECT * FROM users WHERE id = $newUserId");
    $data = $statement->fetch();

    echo "Регистрация успешна!<br>";
    echo "Данные нового пользователя:<br>";
    echo "Имя: " . $data['name'] . "<br>";
    echo "Email: " . $data['email'] . "<br>";
}
require_once './registration_form.php';
?>
