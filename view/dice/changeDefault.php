<?php
namespace Anax\View;

?>

<h1>Tärningsspel 100</h1>

<p>
    Vill du ändra inställningarna?
</p>

<form method=post>
    <fieldset>
        <legend>Ändra inställningarna</legend>
        <p><label>Antal spelare:  <input type="text" name="newNoOfPlayers" value="<?= $noOfPlayers ?>"</label></p>
        <p><label>Antal tärningar:  <input type="text" name="newNoOfDices" value="<?= $noOfDices ?>"</label></p>
        <input type="submit" name="change" value="Ändra">
    </fieldset>
</form>
