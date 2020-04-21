<?php
namespace Anax\View;

?>

<h1>Tärningsspel 100</h1>

<h4>Bestäm startordningen</h4>

<table>
    <tr>
        <th>Spelare</th>
        <th>Namn</th>
        <th>Poäng</th>
    </tr>
    <?php for ($i = 0; $i < $noOfPlayers; $i++) : ?>
        <tr>
            <?php $player = $game->getCurrentPlayer($i) ?>
            <?php $nr = $i + 1 ?>
            <td><?= $nr ?></td>
            <td><?= $player->getName() ?></td>
            <?php if ($roll) : ?>
                <td>
                    <?php $value = $player->getGraphicValues()[0] ?>
                    <p class="dice-sprite">
                        <i class="dice-sprite <?= $value ?>"></i>
                    </p>
                </td>
            <?php else : ?>
                <td></td>
            <?php endif ?>
        </tr>
    <?php endfor ?>
</table>

<div>
    <p>Slå en tärning för att bestämma startordningen. Högst värde först börjar.</p>

    <?php if ($roll === null) : ?>
        <form class="dice100" method=post>
            <input type="submit" name="roll" value="Bestäm startordning">
        </form>
    <?php else : ?>
        <?php $nr = $startOrder + 1 ?>
        <p>Spelare <?= $nr ?> börjar! Lycka till allihop!</p>

        <form class="dice100" method=post>
            <input type="submit" name="start" value="Starta spelet">
        </form>
    <?php endif ?>
</div>

<br><br>
