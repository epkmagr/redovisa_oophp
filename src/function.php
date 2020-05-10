<?php

/**
 * Sanitize value for output in view.
 *
 * @param string $value to sanitize
 *
 * @return string beeing sanitized
 */
function esc($value)
{
    return htmlentities($value);
}



/**
 * Function to create links for sorting.
 *
 * @param string $column the name of the database column to sort by
 *
 * @return string with button to order by column.
 */
function orderby($column)
{
    return <<<EOD
<span class="orderby">
<button name="order" type="submit" value="{$column} asc">&darr;</button>
<button name="order" type="submit" value="{$column} desc">&uarr;</button>
</span>
EOD;
}

/**
 * Function to create links for hits per page.
 *
 * @param int $column the name of the database column to sort by
 *
 * @return string with button to order by column.
 */
function hitsPerPage($hits)
{
    return <<<EOD
<span class="orderby">
<button name="hits" type="submit" value="{$hits}">{$hits}</button>
</span>
EOD;
}

/**
 * Function to create links for current page.
 *
 * @param int $currentPage the name of the database column to sort by
 *
 * @return string with button to order by column.
 */
function getCurrentPage($currentPage)
{
    return <<<EOD
<span class="orderby">
<button name="currentPage" type="submit" value="{$currentPage}">{$currentPage}</button>
</span>
EOD;
}

/**
 * Function to show the movie images.
 *
 * @param string $image the name of the image of the movie
 *
 * @return string with image in cimage
 */
function viewImage($name)
{
    $filename = array_slice(explode('/', rtrim($name, '/')), -1)[0];
    return <<<EOD
    <img class="thumb" src="../../htdocs/cimage/img.php?src={$filename}&w=100&h=100&sharpen&crop-to-fit">
EOD;
}

/**
 * Get the value from POST as an array or return default value.
 *
 * @param mixed $request     to look for, or value array
 * @param mixed $key     to look for, or value array
 * @param mixed $default value to set if key does not exists
 *
 * @return mixed value from POST or the default value
 */
function getPostParams($request, $key, $default = null)
{
    if (is_array($key)) {
        foreach ($key as $val) {
            $post[$val] = $request->getPost($val);
        }
        return $post;
    }
}

/**
 * Create a slug of a string, to be used as url.
 *
 * @param string $str the string to format as slug.
 *
 * @return str the formatted slug.
 */
function slugify($str)
{
    $str = mb_strtolower(trim($str));
    $str = str_replace(['å','ä'], 'a', $str);
    $str = str_replace('ö', 'o', $str);
    $str = preg_replace('/[^a-z0-9-]/', '-', $str);
    $str = trim(preg_replace('/-+/', '-', $str), '-');
    return $str;
}

/**
 * Function to create links for sorting.
 *
 * @param string $id the id of the row to delete.
 *
 * @return string with button to order by column.
 */
function deleteButton(int $id)
{
    return <<<EOD
<span class="adminButton">
<button name="doDelete" type="submit" value="delete {$id}">
    <i class="fa fa-trash-alt" aria-hidden="true"></i>
</button>
</span>
EOD;
}

/**
 * Function to create links for sorting.
 *
 * @param string $id the id of the row to edit.
 *
 * @return string with button to order by column.
 */
function editButton(int $id)
{
    return <<<EOD
<span class="adminButton">
<button name="doEdit" type="submit" value="edit {$id}">
    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
</button>
</span>
EOD;
}

/**
 * Function that divides timestamp into date and time with
 * newline.
 *
 * @param string $timestamp the timestamp to divide.
 *
 * @return string with button to order by column.
 */
function divideTimestamp(string $timestamp = null)
{
    if ($timestamp != null) {
        $splitTimeStamp = explode(" ",$timestamp);
        $date = $splitTimeStamp[0];
        $time = $splitTimeStamp[1];
        return <<<EOD
{$date}<br>{$time}
EOD;
} else {
    return "";
}
}
