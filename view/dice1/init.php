<?php
namespace Anax\View;

?>

<h1>Tärningsspel 100</h1>

<p>
    Byt gärna spelarnas namn (utom datorn), annars blir det Spelare 2, Spelare 3 etc.
</p>

<form class="dice100" method=post>
    <fieldset>
        <legend>Ange spelarnas namn</legend>
        <p><label>Namn 1:  <input type="text" name="name1" value="Datorn" readonly</label></p>
        <?php for ($i = 2; $i <= $noOfPlayers; $i++) : ?>
            <p><label>Namn <?= $i ?>:  <input type="text" name="name<?= $i ?>" value="Spelare <?= $i ?>"</label></p>
        <?php endfor ?>
        <input type="submit" name="doit" value="Bestäm startordning">
    </fieldset>
</form>
