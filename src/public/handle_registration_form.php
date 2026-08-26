<?php

$name = $_GET['name'];
$email = $_GET['email'];
$password = $_GET['psw'];
$passwordRepeat = $_GET['psw-repeat'];

if (strlen($name) == 0) {
    echo "Имя обязательно для заполнения";
    exit;
}

if(strlen($name) < 2) {
    echo "Имя должно содержать содержать не менее 2 символов";
    exit;
}

if (strlen($email) < 3) {
    echo "Email слишком короткий";
    exit;
}

if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    echo "Некорректный формат Email";
    exit;
}

if (strlen($password) < 6) {
    echo "Пароль должен содержать не менее 6 символов";
    exit;
}

if ($password != $passwordRepeat) {
    echo "Пароли не совпадают";
    exit;
}

$pdo = new PDO('pgsql:host=postgres_db;port=5432;dbname=mydb', 'user', 'pass');

$sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':name' => $name,
    ':email' => $email,
    ':password' => $password
]);

$newUserId = $pdo->lastInsertId();

$statement = $pdo->query("SELECT * FROM users WHERE id = $newUserId");
$data = $statement->fetch();

echo "Регистрация успешна!<br>";
echo "Данные нового пользователя:<br>";
echo "Имя: " . $data['name'] . "<br>";
echo "Email: " . $data['email'] . "<br>";


