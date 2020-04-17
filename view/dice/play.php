<?php
namespace Anax\View;

?>

<h1>Tärningsspel 100</h1>

<h4>Poängställning</h4>

<table>
    <tr>
        <th>Spelare</th>
        <th>Namn</th>
        <th>Poäng</th>
    </tr>
    <tr>
        <td>1</td>
        <td><?= $game->getCurrentPlayer(0)->getName() ?></td>
        <td><?= $game->getCurrentPlayer(0)->getScore() ?></td>
    </tr>
    <tr>
        <td>2</td>
        <td><?= $game->getCurrentPlayer(1)->getName() ?></td>
        <td><?= $game->getCurrentPlayer(1)->getScore() ?></td>
    </tr>
</table>

<div>
    <h4>Aktuell spelare</h4>

    <p>Antalet tärningar per runda: <?= $noOfDices ?>.</p>

    <?php if ($startOrder === 0) : ?>
        <p>Spelare 1: <?= $game->getCurrentPlayer(0)->getName() ?></p>
    <?php endif ?>

    <?php if ($startOrder === 1) : ?>
        <p>Spelare 2: <?= $game->getCurrentPlayer(1)->getName() ?></p>
    <?php endif ?>

    <?php if ($invalidNumber) : ?>
        <form class="dice100" method=post>
            <input type="submit" name="roundEnd" value="Lämna över turen">
        </form>
    <?php else : ?>
        <form class="dice100" method=post>
            <input type="submit" name="roll" value="Slå tärningarna">
            <input type="submit" name="roundEnd" value="Spara & avsluta rundan">
        </form>
    <?php endif ?>

    <?php if ($roll && $values != null) : ?>
        <p class="dice-sprite">
            <?php foreach ($values as $value) : ?>
                <i class="dice-sprite dice-<?= $value ?>"></i>
            <?php endforeach; ?>
        </p>

        <p><?= implode(", ", $values) ?></p>

        <p>Summa tärningar i rundan: <?= $tmpScore?></p>
    <?php endif ?>
</div>

<br><br>
