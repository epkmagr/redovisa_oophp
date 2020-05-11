<?php
if (!$res) {
    return;
}
?>

<form class="contentShow" method="post">
    <p>Items per page:
        <?= hitsPerPage(2); ?>
        <?= hitsPerPage(4); ?>
        <?= hitsPerPage(8); ?>
    </p>

    <table>
        <tr class="first">
            <th>Id <?= orderby("id") ?></th>
            <th>Title <?= orderby("title") ?></th>
            <th>Type <?= orderby("type") ?></th>
            <th>Published <?= orderby("published") ?></th>
            <th>Created <?= orderby("created")?> </th>
            <th>Updated <?= orderby("updated") ?></th>
            <th>Deleted <?= orderby("deleted") ?></th>
            <th>Actions</th>
        </tr>
    <?php $id = -1; foreach ($res as $row) :
        $id++; ?>
        <tr>
            <td><?= $row->id ?></td>
            <td><?= $row->title ?></td>
            <td><?= $row->type ?></td>
            <td><?= divideTimestamp($row->published) ?></td>
            <td><?= divideTimestamp($row->created) ?></td>
            <td><?= divideTimestamp($row->updated) ?></td>
            <td><?= divideTimestamp($row->deleted) ?></td>
            <td>
                <?= editButton($row->id) ?>
                <?= deleteButton($row->id) ?>
            </td>
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
