<?php
// Restore the database to its original settings

if (isset($reset)) {
    $output = "<p>The command was: <code>$command</code>.<br>The command exit status was $status."
        . "<br>The output from the command was:</p><pre>"
        . print_r($output, 1);
}

?>
<form method="post">
    <input type="submit" name="reset" value="Reset database">

    <?php if (isset($output)) : ?>
        <?= $output ?>
    <?php endif ?>
</form>
