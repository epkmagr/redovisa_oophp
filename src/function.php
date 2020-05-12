<?php

use Epkmagr\MyTextFilter\MyTextFilter;

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
 * Parse the text with the given filters for output in view.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 *
 * @param MyTextFilter $myTextFilter   The text filter objcet to use.
 * @param string $text   The text to filter.
 * @param array  $filter Array of filters to use. Valid values are
 *                       "bbcode", "link", "markdown" and "nl2br".
 *
 * @return string beeing sanitized
 */
function parseText(MyTextFilter $myTextFilter, $text, string $filters = "")
{
    $str = "";
    if ($filters != "") {
        if (strpos($filters, ",") !== false) {
            $filterArray = explode(",", $filters);
        } else {
            $filterArray[] = $filters;
        }
        $filterArray = explode(",", $filters);
        if (strpos($filters, "markdown")) {
            $str = $myTextFilter->parse($text, $filterArray);
        } else {
            $str = "<p>";
            $str = $str . $myTextFilter->parse($text, $filterArray);
            $str = $str . "</p>";
        }
    } else {
        $str = htmlentities($text);
    }
    return $str;
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
<span class="paginate">
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
<span class="paginate">
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
 * Create a path of a string, to be used as url.
 *
 * @param string $str the string to format as path.
 *
 * @return str the formatted path.
 */
function pathify($str)
{
    $str = mb_strtolower(trim($str));
    $str = str_replace(['å','ä'], 'a', $str);
    $str = str_replace('ö', 'o', $str);
    $str = explode(' ', trim($str))[0];
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
    <i class="fa fa-trash-o" aria-hidden="true"></i>
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
        $splitTimeStamp = explode(" ", $timestamp);
        $date = $splitTimeStamp[0];
        $time = $splitTimeStamp[1];
        return <<<EOD
{$date}<br>{$time}
EOD;
    } else {
        return "";
    }
}

/**
 * Function that gets the date from a timestamp into date and time with
 * newline.
 *
 * @param string $timestamp the timestamp to divide.
 *
 * @return string with button to order by column.
 */
function getDateFromTimestamp(string $timestamp = null)
{
    if ($timestamp != null) {
        $splitTimeStamp = explode(" ", $timestamp);
        return $splitTimeStamp[0];
    } else {
        return "";
    }
}
