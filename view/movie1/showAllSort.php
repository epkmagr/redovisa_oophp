<?php
if (!$res) {
    return;
}
?>

<form method="post">
    <table>
        <tr class="first">
            <th>Rad</th>
            <th>Id <?= orderby("id") ?></th>
            <th>Bild <?= orderby("image") ?></th>
            <th>Titel <?= orderby("title") ?></th>
            <th>År <?= orderby("year") ?></th>
        </tr>
    <?php $id = -1; foreach ($res as $row) :
        $id++; ?>
        <tr>
            <td><?= $id ?></td>
            <td><?= $row->id ?></td>
            <td><img class="thumb" src="../<?= $row->image ?>"></td>
            <td><?= $row->title ?></td>
            <td><?= $row->year ?></td>
        </tr>
    <?php endforeach; ?>
    </table>
</form>
