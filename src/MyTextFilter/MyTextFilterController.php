<?php

namespace Epkmagr\MyTextFilter;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;

/**
 * A sample controller to show how a controller class can be implemented.
 * The controller will be injected with $app if implementing the interface
 * AppInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class MyTextFilterController implements AppInjectableInterface
{
    use AppInjectableTrait;

    /**
    * @var TextFilter $filter a TextFilter class to help formatting text
    * on webpages.
    */
    private $filter;

    /**
     * The initialize method is optional and will always be called before the
     * target method/action. This is a convienient method where you could
     * setup internal properties that are commonly used by several methods.
     *
     * @return void
     */
    public function initialize() : void
    {
        // Use to initialise member variables.
        $this->filter= new MyTextFilter();
    }

    /**
     * This is the index method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function indexAction() : string
    {
        // Deal with the action and return a response.
        return "INDEX!!";
    }

    /**
     * This is the bbcode method action that shows the login form, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function bbcodeAction() : object
    {
        // Framework services
        $page = $this->app->page;

        $title = "BBCode";

        $data = [
            "myTextFilter" => $this->filter ?? null,
        ];

        $page->add("mytextfilter/bbcode", $data);
        // $page->add("textfilter1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the link method action that shows the login form, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function linkAction() : object
    {
        // Framework services
        $page = $this->app->page;

        $title = "Link";

        $data = [
            "myTextFilter" => $this->filter ?? null,
        ];

        $page->add("mytextfilter/clickable", $data);
        // $page->add("textfilter1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the markdown method action that shows the login form, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function markdownAction() : object
    {
        // Framework services
        $page = $this->app->page;

        $title = "Markdown";

        $data = [
            "myTextFilter" => $this->filter ?? null,
        ];

        $page->add("mytextfilter/markdown", $data);
        // $page->add("textfilter1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the nl2br method action that shows the login form, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function nl2brAction() : object
    {
        // Framework services
        $page = $this->app->page;

        $title = "Nl2br";

        $data = [
            "myTextFilter" => $this->filter ?? null,
        ];

        $page->add("mytextfilter/nl2br", $data);
        // $page->add("textfilter1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }
}
