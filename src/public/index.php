<?php

$pdo = new PDO('pgsql:host=postgres_db;port=5432;dbname=mydb', 'user', 'pass');

//$pdo->exec("INSERT INTO users (name, email, password) VALUES ('zevs', 'zevs@mail.ru', '123456')");

$statement = $pdo->query("SELECT * FROM users WHERE id = 1");
echo '<pre>';
$data = $statement->fetch();

print_r($data);

echo '</pre>';