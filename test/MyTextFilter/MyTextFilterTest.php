<?php

namespace Epkmagr\MyTextFilter;

use \PHPUnit\Framework\TestCase;

/**
 * Test class for the MyTextFilter class.
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 * @SuppressWarnings(PHPMD.UnusedPrivateField)
 */
class MyTextFilterTest extends TestCase
{
    /**
     * Test bbcode2html ok.
     *
     * @return void
     */
    public function testBbcode2htmlOk()
    {
        $filter = new MyTextFilter();

        $html = "[b]Bold text[/b] [i]Italic text[/i] [url=http://dbwebb.se]a link to dbwebb[/url]";
        $exp  = "<strong>Bold text</strong> <em>Italic text</em> <a href=\"http://dbwebb.se\">a link to dbwebb</a>";
        $res = $filter->bbcode2html($html);
        $this->assertEquals($exp, $res, "bbcode2html failed: '$res'");
    }

    /**
     * Test link ok.
     *
     * @return void
     */
    public function testMakeClickableOk()
    {
        $filter = new MyTextFilter();

        $html = "<p>This link should for example be made clickable: http://dbwebb.se.</p>";
        $exp  = "<p>This link should for example be made clickable: <a href=\'http://dbwebb.se\'>http://dbwebb.se</a>.</p>";
        $res = $filter->makeClickable($html);
        $this->assertEquals($exp, $res, "nl2br failed: '$res'");
    }

    /**
     * Test markdown ok.
     *
     * @return void
     */
    public function testMarkdownOk()
    {
        $filter = new MyTextFilter();

        $html = "Header level 2 {#id2}
---------------------

Here comes another paragraph, now intended as blockquote.

1. Ordered list
2. Ordered list again

> This should be a blockquote.";
        $exp  = "<h2 id=\"id2\">Header level 2</h2>

<p>Here comes another paragraph, now intended as blockquote.</p>

<ol>
<li>Ordered list</li>
<li>Ordered list again</li>
</ol>

<blockquote>
  <p>This should be a blockquote.</p>
</blockquote>
";
        $res = $filter->markdown($html);
        $this->assertEquals($exp, $res, "markdown failed: '$res'");
    }

    /**
     * Test nl2br ok.
     *
     * @return void
     */
    public function testNl2BrOk()
    {
        $filter = new MyTextFilter();

        $html = "One line.\nAnother line.";
        $exp  = "One line.<br />\nAnother line.";
        $res = $filter->nl2br($html);
        $this->assertEquals($exp, $res, "nl2br failed: '$res'");
    }

    /**
     * Test nl2br ok.
     *
     * @return void
     */
    public function testNl2BrOk2()
    {
        $filter = new MyTextFilter();

        $html = "This\r\nis\n\ra\nstring\r";
        $exp  = "This<br />\r\nis<br />\n\ra<br />\nstring<br />\r";
        $res = $filter->nl2br($html);
        $this->assertEquals($exp, $res, "nl2br failed: '$res'");
    }

    /**
     * Test stripTags ok.
     *
     * @return void
     */
    public function testStripTagsOk()
    {
        $filter = new MyTextFilter();

        $html = "<h2>Header level 2</h2>

<p>Here comes another paragraph, now intended as blockquote.</p>

<ol>
<li>Ordered list</li>
<li>Ordered list again</li>
</ol>

<blockquote>
  <p>This should be a blockquote.</p>
</blockquote>
";
        $exp  = "Header level 2

Here comes another paragraph, now intended as blockquote.


Ordered list
Ordered list again



  This should be a blockquote.

";
        $res = $filter->stripTags($html);
        $this->assertEquals($exp, $res, "stripTags failed: '$res'");
    }

    /**
     * Test ecs ok.
     *
     * @return void
     */
    public function testEcsOk()
    {
        $filter = new MyTextFilter();

        $html = "ÖÖ<b>I am cool</b><i>Wow</i>";
        $exp  = "&Ouml;&Ouml;&lt;b&gt;I am cool&lt;/b&gt;&lt;i&gt;Wow&lt;/i&gt;";
        $res = $filter->ecs($html);
        $this->assertEquals($exp, $res, "ecs failed: '$res'");
    }

    /**
     * Test parse with ecs ok.
     *
     * @return void
     */
    public function testParseEcsOk()
    {
        $filter = new MyTextFilter();

        $html = "ÖÖ<b>I am cool</b><i>Wow</i>";
        $exp  = "&Ouml;&Ouml;&lt;b&gt;I am cool&lt;/b&gt;&lt;i&gt;Wow&lt;/i&gt;";
        $res = $filter->parse($html, ["ecs"]);
        $this->assertEquals($exp, $res, "ecs failed: '$res'");
    }

    /**
     * Test parse with ecs ok.
     *
     * @return void
     */
    public function testParseBbCodeStripTagsOk()
    {
        $filter = new MyTextFilter();

        $html = "[b]Bold text[/b] [i]Italic text[/i] [url=http://dbwebb.se]a link to dbwebb[/url]";
        $exp  = "<strong>Bold text</strong> <em>Italic text</em> <a href=\"http://dbwebb.se\">a link to dbwebb</a>";
        $res = $filter->parse($html, ["bbcode", "stripTags"]);
        $this->assertEquals($exp, $res, "ecs failed: '$res'");
    }
}
