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
