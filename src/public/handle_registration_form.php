<?php

$name = $_GET['name'];
$email = $_GET['email'];
$password = $_GET['psw'];
$passwordRepeat = $_GET['psw-repeat'];

$pdo = new PDO('pgsql:host=postgres_db;port=5432;dbname=mydb', 'user', 'pass');


if ($name === '') {
    echo 'поле Name не должно быть пустым';
}elseif (strlen($name) < 2) {
    echo 'в поле Name должно быть не менее двух символов';
}

if ($email === '') {
    echo 'укажите Email';
}elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Email некорректный';
}

if ($password === '') {
    echo 'поле Password не должно быть пустым';
}elseif (strlen($password) < 6) {
    echo 'в поле Password должно быть не менее шести символов';
}

if ($passwordRepeat === '') {
    echo 'Повторите пароль';
} elseif ($password !== $passwordRepeat) {
    echo 'Пароли не совпадают';
}

$stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password) RETURNING id");
$stmt->execute([
    ':name' => $name,
    ':email' => $email,
    ':password' => $password
]);


$newUserId = $stmt->fetchColumn();

echo "<h2>Пользователь успешно создан!</h2>";
echo "<p><strong>ID:</strong> " . $newUserId . "</p>";
echo "<p><strong>Имя:</strong> " . $name . "</p>";
echo "<p><strong>Email:</strong> " . $email . "</p>";
