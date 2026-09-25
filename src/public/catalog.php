<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login_form.php');
    exit;
}

$pdo = new PDO('pgsql:host=postgres_db;port=5432;dbname=mydb', 'user', 'pass');
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();

require_once './catalog_page.php';
