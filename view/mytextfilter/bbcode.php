<?php

namespace Anax\View;

$text = file_get_contents(__DIR__ . "/text/bbcode.txt");
$html = $myTextFilter->bbcode2html($text);

$filters = ["bbcode", "nl2br"];

?>
<title>Show off BBCode</title>

<h1>Showing off BBCode</h1>

<h2>Source in bbcode.txt</h2>
<pre><?= wordwrap(htmlentities($text)) ?></pre>

<h2>Filter BBCode applied, source</h2>
<pre><?= wordwrap(htmlentities($html)) ?></pre>

<h2>Filter BBCode applied, HTML (including nl2br())</h2>
<pre><?= $myTextFilter->nl2br($html) ?></pre>

<h2>The "bbcode", "nl2br" filters applied via parse</h2>
<pre><?= $myTextFilter->parse($text, $filters) ?></pre>
