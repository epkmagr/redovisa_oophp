<?php if ($slugErrorMsg != null) : ?>
    <p class="warning"><?= $slugErrorMsg ?> </p> |
<?php endif ?>

<?php
if (!isset($contentUser)) {
    return;
} ?>

<form class="contentEdit" method="post">
    <fieldset>
    <legend>Edit</legend>
    <input type="hidden" name="contentId" value="<?= esc($content->id) ?>"/>

    <p>
        <label>Title:<br>
        <input type="text" name="contentTitle" value="<?= esc($content->title) ?>"/>
        </label>
    </p>

    <p>
        <label>Path:<br>
        <input type="text" name="contentPath" value="<?= esc($content->path) ?>" required/>
    </p>

    <p>
        <label>Slug:<br>
        <input type="text" name="contentSlug" value="<?= esc($content->slug) ?>"/>
    </p>

    <p>
        <label>Text:<br>
        <textarea name="contentData"><?= esc($content->data) ?></textarea>
    </p>

    <p>
        <label>Type of content:<br>
        <label class="radio">
            Page:
            <?php if ($content->type === "page") : ?>
                <input type="radio" name="contentType" value="page" checked>
            <?php else : ?>
                <input type="radio" name="contentType" value="page" checked>
            <?php endif ?>
        </label>
        <label class="radio">
            Post:
            <?php if ($content->type === "post") : ?>
                <input type="radio" name="contentType" value="post" checked>
            <?php else : ?>
                <input type="radio" name="contentType" value="post">
            <?php endif ?>
        </label>
    </p>

    <p>
        <label for="contentFilter">Filter:  </label>
        <?php $filterArray = explode(",", $content->filter) ?>
        <?php foreach ($validFilters as $row => $value) : ?>
            <?php if (in_array($row, $filterArray)) : ?>
                <input type="checkbox" id="contentFilter" name="contentFilter[]" value='<?= $row ?>' checked>
            <?php else : ?>
                <input type="checkbox" id="contentFilter" name="contentFilter[]" value='<?= $row ?>'>
            <?php endif ?>
           <label for="contentFilter"><?= $row ?></label>
        <?php endforeach; ?>
     </p>

     <p>
        <label>Publish:<br>
        <input type="datetime" name="contentPublish" value="<?= esc($content->published) ?>"/>
     </p>

    <p>
        <button type="submit" name="doSave" value="save"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
        <button type="reset"><i class="fa fa-undo" aria-hidden="true"></i> Reset</button>
        <button type="submit" name="doDelete" value="delete"><i class="fa fa-trash-alt" aria-hidden="true"></i> Delete</button>
    </p>
    </fieldset>
</form>
