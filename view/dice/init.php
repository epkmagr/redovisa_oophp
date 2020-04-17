<?php
namespace Anax\View;

?>

<h1>Tärningsspel 100</h1>

<p>
    Ange spelarnas namn om du vill, annars blir det spelare1 etc.
</p>

<form method=post>
    <fieldset>
        <legend>Ange spelarnas namn</legend>
        <p><label>Namn 1:  <input type="text" name="name1" value="Datorn" readonly</label></p>
        <p><label>Namn 2:  <input type="text" name="name2" value="Spelare1"</label></p>
        <input type="submit" name="doit" value="Starta">
    </fieldset>
</form>
