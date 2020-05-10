<?php if ($slugErrorMsg != null) : ?>
    <p class="warning"><?= $slugErrorMsg ?> </p> |
<?php endif ?>


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
        <input type="text" name="contentPath" value="<?= esc($content->path) ?>"/>
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
        <label for="contentFilter">Filter:  </label>
        <?php $filterArray = explode(",", $content->filter) ?>
        <?php foreach ($filterArray as $row) : ?>
            <input type="checkbox" id="<?= $row ?>" name="<?= $row ?>"
           checked>
           <label for="<?= $row ?>"><?= $row ?></label>
        <?php endforeach; ?>
     </p>

     <p>
         <label>Type:<br>
         <input type="text" name="contentType" value="<?= esc($content->type) ?>"/>
     </p>

     <p>
         <label>Filter:<br>
         <input type="text" name="contentFilter" value="<?= esc($content->filter) ?>"/>
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
