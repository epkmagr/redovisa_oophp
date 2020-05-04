<?php
if (!$res) {
    return;
}
?>

<form method="post">
    <p>Items per page:
        <?= hitsPerPage(2); ?>
        <?= hitsPerPage(4); ?>
        <?= hitsPerPage(8); ?>
    </p>

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
            <td><?= viewImage($row->image) ?></td>
            <td><?= $row->title ?></td>
            <td><?= $row->year ?></td>
        </tr>
    <?php endforeach; ?>
    </table>

    <p>
        Pages:
        <?php for ($i = 1; $i <= $max; $i++) : ?>
            <?= getCurrentPage($i) ?>
        <?php endfor; ?>
    </p>
</form>
