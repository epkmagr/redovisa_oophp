<h1>Guess my number</h1>

<?php if ($tries === 1) : ?>
    <p>Guess a number between 1 and 100! You have <?= $tries ?> try left!</p>
<?php else : ?>
    <p>Guess a number between 1 and 100! You have <?= $tries ?> tries left!</p>
<?php endif ?>

<form method=post>
    <input type="text" name="aGuess">
    <input type="hidden" name="number" value="<?= $number ?>">
    <input type="hidden" name="tries" value="<?= $tries ?>">
    <input type="submit" name="doGuess" value="Make a guess">
    <input type="submit" name="initGame" value="Play again">
    <input type="submit" name="cheat" value="Cheat">
</form>

<?php if ($doGuess) : ?>
    <p>Your guess <?= $aGuess ?> is <b> <?= $res ?></b>!</p>
    <?php if ($tries >= 1 && $res != "correct") : ?>
        <p>Guess again!</p>
    <?php endif ?>
<?php endif ?>

<?php if ($cheat) : ?>
    <p>The game's current number is <?= $number ?></p>
<?php endif ?>
