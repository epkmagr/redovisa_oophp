<?php
namespace Anax\View;

?>

<h1>Tärningsspel 100</h1>

<h4>Spelare 1: <?= $player1->getName() ?> med poängsumma: <?= $player1->getScore() ?></h4>

<?php if ($startOrder === 0) : ?>
    <form method=post>
        <input type="submit" name="roll" value="Slå tärningarna">
        <input type="submit" name="roundEnd" value="Spara & avsluta rundan">
    </form>
<?php endif ?>

<h4>Spelare 2: <?= $player2->getName() ?> med poängsumma: <?= $player2->getScore() ?></h4>

<?php if ($startOrder === 1) : ?>
    <form method=post>
        <input type="submit" name="roll" value="Slå tärningarna">
        <input type="submit" name="roundEnd" value="Spara & avsluta rundan">
    </form>
<?php endif ?>
