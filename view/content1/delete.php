<form class="contentEdit" method="post">
    <fieldset>
    <legend>Delete</legend>

    <input type="hidden" name="contentId" value="<?= esc($contentId) ?>"/>

    <p>
        <label>Title:<br>
            <input type="text" name="contentTitle" value="<?= esc($contentTitle) ?>" readonly/>
        </label>
    </p>

    <p>
        <button type="submit" name="doDelete" value="delete"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
    </p>
    </fieldset>
</form>
