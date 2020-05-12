<?php
if (!$res) {
    return;
}
?>

<article>

<?php foreach ($res as $row) : ?>
<section>
    <header>
        <h1><a href="showBlogPost/<?= esc($row->slug) ?>"><?= esc($row->title) ?></a></h1>
        <?php if ($row->status === "notPublished") : ?>
            <p><i>Will be published: <time datetime="<?= esc($row->published_iso8601) ?>" pubdate><?= esc($row->published) ?></time></i></p>
        <?php else : ?>
            <p><i>Published: <time datetime="<?= esc($row->published_iso8601) ?>" pubdate><?= esc($row->published) ?></time></i></p>
        <?php endif ?>
        <?php if ($row->deleted != null) : ?>
            <p><i>Deleted: <?= getDateFromTimestamp($row->deleted) ?></i></p>
        <?php endif ?>
    </header>
    <?php if ($row->deleted === null) : ?>
        <p class="blogPost">
            <?= esc($row->data) ?>
        </p>
    <?php endif ?>
</section>
<?php endforeach; ?>

</article>
