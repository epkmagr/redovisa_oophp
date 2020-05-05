<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<navbar class="movieNavbar">
    <h4></h4>
    <a href="select">SELECT *</a> |
    <?php if ($movieUser == null) : ?>
        <a href="login">Login</a> |
    <?php endif ?>
    <?php if ($movieUser) : ?>
        <a href="logout">Logout <i>(User: <?= $movieUser ?>)</i></a> |
    <?php endif ?>
    <br>
    <a href="showAll">Show all movies</a> |
    <a href="reset">Reset database</a> |
    <a href="searchTitle">Search title</a> |
    <a href="searchYear">Search year</a> |
    <a href="movieSelect">Select</a> |
    <a href="showAllSort">Show all sortable</a> |
    <a href="showAllPaginate">Show all paginate</a>
</navbar>

<h1>My Movie Database</h1>

<main>
