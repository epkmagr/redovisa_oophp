<?php
namespace Anax\View;

?>

<h1>Tärningsspel 100</h1>

<p>
    Antalet spelare är <?= $noOfPlayers ?>.
</p>

<form method=post>
    <fieldset>
        <legend>Ange spelarnas namn</legend>
        <p><label>Namn 1:  <input type="text" name="name1"</label></p>
        <p><label>Namn 2:  <input type="text" name="name2" value="Datorn" readonly</label></p>
        <input type="submit" name="doit" value="Starta">
    </fieldset>
</form>
