<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://use.fontawesome.com/e5579368c4.js"></script>
</head>
<body>

<navbar class="contentNavbar">
    <h4></h4>
    <a href="showAll">Show all content</a> |
    <?php if ($contentUser) : ?>
        <a href="reset">Reset database</a> |
        <a href="admin">Admin</a> |
        <a href="create">Create</a> |
    <?php endif ?>
    <a href="showPages">View pages</a> |
    <a href="showBlog">View blog</a> |
    <?php if ($contentUser == null) : ?>
        <a href="login">Login</a> |
    <?php endif ?>
    <?php if ($contentUser) : ?>
        <a href="logout">Logout <i>(User: <?= $contentUser ?>)</i></a> |
    <?php endif ?>
</navbar>

<h1>My Content Database</h1>

<main>
