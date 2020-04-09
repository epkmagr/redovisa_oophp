<?php require __DIR__ . "/headerRedirect.php"; ?>
<?php require __DIR__ . "/../autoloadRedirect.php"; ?>
<?php require __DIR__ . "/../config.php"; ?>

<?php if (isset($_SESSION["game"])) : ?>
    <?php $number = $_SESSION["game"]->number(); ?>
<?php endif ?>

<h1>Congratulations, your guess <?= $number ?> is correct!</h1>

<img src="../img/happy.png" class="smiley" alt="Happy face image">

<form class="playAgain" method=post action="../index.php">
    <input type="submit" name="initGame" value="Play again">
</form>

<?php require __DIR__ . "/footer.php"; ?>
