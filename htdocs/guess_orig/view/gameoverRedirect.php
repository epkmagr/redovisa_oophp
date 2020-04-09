<?php require __DIR__ . "/headerRedirect.php"; ?>
<?php require __DIR__ . "/../autoloadRedirect.php"; ?>
<?php require __DIR__ . "/../config.php"; ?>

<?php if (isset($_SESSION["game"])) : ?>
    <?php $number = $_SESSION["game"]->number(); ?>
<?php endif ?>

<h1>Sorry, the game is over!</h1>

<img src="../img/sad.png" class="smiley" alt="Sad face image">

<p>The correct number was <?= $number ?>!</p>

<form class="playAgain" method=post action="../index.php">
    <input type="submit" name="initGame" value="Play again">
</form>

<?php require __DIR__ . "/footer.php"; ?>
