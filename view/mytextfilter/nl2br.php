<?php

namespace Anax\View;

$text = file_get_contents(__DIR__ . "/text/nl2br.txt");
$str1 = "One line.\nAnother line.";

?>
<title>Show off nl2br</title>

<h1>Showing off nl2br</h1>

<h2>Source in nl2br.txt</h2>
<pre>
    <?= $text ?>
    <?= $str1 ?>
</pre>

<h2>Filter nl2br applied, HTML</h2>
<pre>
    <?= $myTextFilter->nl2br($text) ?>
    <?= $myTextFilter->nl2br($str1) ?>
</pre>
