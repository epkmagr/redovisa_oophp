<?php
namespace Anax\View;

?>

<h1>Tärningsspel 100</h1>

<p>
    Vill du ändra inställningarna?
</p>

<?php if ($changeMessage != null) : ?>
    <h3 style="color:red"><?= $changeMessage ?> </h3>
<?php endif ?>

<form method=post>
    <fieldset>
        <legend>Ändra inställningarna</legend>
        <p><label>Antal spelare:  <input type="text" name="newNoOfPlayers" value="<?= $noOfPlayers ?>" min="2" max="6" required</label></p>
        <p><label>Antal tärningar:  <input type="text" name="newNoOfDices" value="<?= $noOfDices ?>" min="1" max="10" required</label></p>
        <input type="submit" name="change" value="Ändra">
    </fieldset>
</form>
