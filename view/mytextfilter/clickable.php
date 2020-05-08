<?php

namespace Anax\View;

$text = file_get_contents(__DIR__ . "/text/clickable.txt");
$html = $myTextFilter->makeClickable($text);
$filters = ["link", "nl2br"];

?>
<title>Show off Clickable</title>

<h1>Showing off Clickable</h1>

<h2>Source in clickable.txt</h2>
<pre><?= wordwrap(htmlentities($text)) ?></pre>

<h2>Source formatted as HTML</h2>
<?= $text ?>

<h2>Filter Clickable applied, source</h2>
<pre><?= wordwrap(htmlentities($html)) ?></pre>

<h2>Filter Clickable applied, HTML</h2>
<pre><?= wordwrap(htmlentities($html)) ?></pre>
<pre><?= $html ?></pre>

<h2>The "link", "nl2br" filters applied via parse</h2>
<pre><?= $myTextFilter->parse($text, $filters) ?></pre>
