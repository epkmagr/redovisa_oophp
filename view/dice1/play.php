<?php
namespace Anax\View;

?>

<h1>Tärningsspel 100</h1>

<div class="presentation">
    <div class="col1">
        <h4>Poängställning</h4>

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
                    <td><?= $player->getScore() ?></td>
                </tr>
            <?php endfor ?>
        </table>
    </div>

    <div class="col2">
        <h4>Histogram</h4>
        <pre><?= $game->getHistogram() ?></pre>
    </div>

</div>

<div>
    <?php $nr = $startOrder + 1 ?>
    <h4>Aktuell spelare:  <?= $nr ?></h4>

    <p>Antalet tärningar per runda: <?= $noOfDices ?>.</p>

    <?php $nr = $startOrder + 1 ?>
    <p>Namn på spelare <?= $nr ?>: <?= $game->getCurrentPlayer($startOrder)->getName() ?></p>

    <?php if ($invalidNumber) : ?>
        <form class="dice100" method=post>
            <input type="submit" name="roundEnd" value="Lämna över turen">
        </form>
    <?php else : ?>
        <?php if ($startOrder === 0) : ?>
            <?php if ($computerContinues) : ?>
                <form class="dice100" method=post>
                    <input type="submit" name="roll" value="Simulera datorns runda">
                </form>
            <?php else : ?>
                <form class="dice100" method=post>
                    <input type="submit" name="roundEnd" value="Lämna över turen">
                </form>
            <?php endif ?>
        <?php else : ?>
            <form class="dice100" method=post>
                <input type="submit" name="roll" value="Slå tärningarna">
                <input type="submit" name="roundEnd" value="Spara & avsluta rundan">
            </form>
        <?php endif ?>
    <?php endif ?>

    <?php if ($roll && $graphicValues != null) : ?>
        <p class="dice-sprite">
            <?php foreach ($graphicValues as $value) : ?>
                <i class="dice-sprite <?= $value ?>"></i>
            <?php endforeach; ?>
        </p>

        <p>
            Summa tärningar i rundan: <?= $tmpScore?>.
            <?php if ($invalidNumber) : ?>
                Tyvärr du fick 1, turen går över till nästa spelare!
            <?php endif ?>
        </p>
    <?php endif ?>
</div>

<br><br>
