<?php
namespace Anax\View;

?>

<h1>Tärningsspel 100</h1>

<p>
    Antalet spelare är <?= $noOfPlayers ?> och antalet tärningar per hand är <?= $noOfDices ?>.
</p>

<form method=post>
    <fieldset>
        <legend>Ange spelarnas namn</legend>
        <label for="changeDefault">Vill du ändra på inställningarna?</label>
        <p><input type="radio" name="yesOrNo" value= "Ja">Ja</input></p>
        <p><input type="radio" name="yesOrNo"  value= "Nej" checked>Nej (Default)</input></p>
        <input type="submit" name="changeDefault" value="Initiera spelet">
    </fieldset>
</form>
