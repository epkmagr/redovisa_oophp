<?php
if (!$res) {
    return;
}
?>

<div class="contentShow">
    <table>
        <tr class="first">
            <th>Id</th>
            <th>Title</th>
            <th>Type</th>
            <th>Published</th>
            <th>Created</th>
            <th>Updated</th>
            <th>Deleted</th>
            <th>Path</th>
            <th>Slug</th>
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
            <td><?= $row->path ?></td>
            <td><?= $row->slug ?></td>
        </tr>
    <?php endforeach; ?>
    </table>
</div>
