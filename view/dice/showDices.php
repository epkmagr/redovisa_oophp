<?php
namespace Epkmagr\Dice;

/**
 * Throw a hand of dices.
 */

$dice = new DiceGraphic();
$rolls = 6;
$res = [];
$class = [];
for ($i = 0; $i < $rolls; $i++) {
    $res[] = $dice->roll();
    $class[] = $dice->graphic();
}

?>
<!doctype html>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">

<h1>Rolling <?= $rolls ?> graphic dices</h1>

<p class="dice-sprite">
    <?php foreach ($class as $value) : ?>
        <i class="dice-sprite <?= $value ?>"></i>
    <?php endforeach; ?>
</p>

<p class="dice-utf8">
    <?php foreach ($class as $value) : ?>
        <i class="<?= $value ?>"></i>
    <?php endforeach; ?>
</p>

<p>
    <?= implode(", ", $res) ?>
</p>

<p>
    Sum is: <?= array_sum($res) ?>.
</p>

<p>
    Average is: <?= round(array_sum($res) / $rolls, 2) ?>.
</p>
