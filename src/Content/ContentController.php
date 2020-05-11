<?php

namespace Epkmagr\Content;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;
use Epkmagr\MyTextFilter\MyTextFilter;

/**
 * A sample controller to show how a controller class can be implemented.
 * The controller will be injected with $app if implementing the interface
 * AppInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class ContentController implements AppInjectableInterface
{
    use AppInjectableTrait;

    /**
    * @var string $db a sample member variable that gets initialised
    * @var DatabaseHelper $helper a support class to help with the database
    * @var MyTextFilter $textFilter a class for filtering text
     */
    private $db;
    private $helper;
    private $textFilter;

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
        // connect to the database
        $this->app->db->connect();
        $this->db = $this->app->db;
        $this->helper = new DatabaseHelper($this->db, "content");
        $this->textFilter = new MyTextFilter();
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
     * This is the showAll method action that shows all the content, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function showAllAction() : object
    {
        // Framework services
        $page = $this->app->page;

        $title = "Content database | oophp";

        $res = $this->helper->getAllRows();

        $page->add("content1/header");
        $page->add("content1/showAll", [
            "res" => $res,
        ]);
        // $this->app->page->add("content1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the reset method GET action that shows the reset, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function resetActionGet() : object
    {
        // Framework services
        $page = $this->app->page;
        $session = $this->app->session;

        $title = "Resetting the database";

        $reset = $session->get("reset");
        $session->set("reset", null);

        $page->add("content1/header");

        if ($reset) {
            $dbConfig = $this->app->configuration->load("database");
            $resetInfo = $this->helper->getCommand($dbConfig['config']);
            $page->add("content1/reset", [
                "reset" => $reset,
                "command" => $resetInfo[0],
                "status" => $resetInfo[1],
                "output" => $resetInfo[2],
            ]);
        } else {
            $page->add("content1/reset", [
                "reset" => null,
            ]);
        }

        // $this->app->page->add("content1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the reset method POST action that actually resets the movie
     * database, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function resetActionPost() : object
    {
        // Framework services
        $response = $this->app->response;
        $request = $this->app->request;
        $session = $this->app->session;

        $reset = $request->getPost("reset");
        $session->set("reset", $reset);

        return $response->redirect("content1/reset");
    }

    /**
     * This is the create method GET action that shows the movie
     * that can be edited or added in the movie database, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function createActionGet() : object
    {
        // Framework services
        $page = $this->app->page;

        $title = "Create content";

        $page->add("content1/header");
        $page->add("content1/create");
        // $this->app->page->add("content1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the create method POST action that shows the movie
     * that can be edited or added in the movie database, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function createActionPost() : object
    {
        // Framework services
        $request = $this->app->request;
        $response = $this->app->response;

        $doCreate = $request->getPost("doCreate");
        $contentTitle = $request->getPost("contentTitle");

        if ($doCreate === "create") {
            $id = $this->helper->insertRowAndReturnLastId($contentTitle);
            return $response->redirect("content1/edit/{$id}");
        } else {
            return $response->redirect("content1/create");
        }
    }

    /**
     * This is the admin method GET action that shows all the content, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function adminActionGet() : object
    {
        // Framework services
        $page = $this->app->page;

        $title = "Admin content | oophp";

        $res = $this->helper->getAllRows();

        $page->add("content1/header");
        $page->add("content1/admin", [
            "res" => $res,
        ]);
        // $this->app->page->add("content1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the admin method POST action that shows all the content, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function adminActionPost() : object
    {
        // Framework services
        $request = $this->app->request;
        $response = $this->app->response;

        $doDeleteStr = explode(" ", $request->getPost("doDelete"));
        $doEditStr = explode(" ", $request->getPost("doEdit"));

        if ($doDeleteStr[0] === "delete") {
            return $response->redirect("content1/delete/{$doDeleteStr[1]}");
        } elseif ($doEditStr[0] === "edit") {
            return $response->redirect("content1/edit/{$doEditStr[1]}");
        } else {
            return $response->redirect("content1/admin");
        }
    }

    /**
     * This is the edit method GET action that shows the content
     * that can be edited in the content database, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function editActionGet(int $id = 0) : object
    {
        // Framework services
        $page = $this->app->page;
        $session = $this->app->session;

        $title = "Edit content";

        $slugErrorMsg = $session->get("slugErrorMsg");
        $session->set("slugErrorMsg", null);

        $content = $this->helper->getRow($id);

        $page->add("content1/headerUpOneLevel");
        $page->add("content1/edit", [
            "slugErrorMsg" => $slugErrorMsg,
            "content" => $content,
            "validFilters" => $this->textFilter->getFilters(),
        ]);
        // $this->app->page->add("content1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the edit method POST action that shows the movie
     * that can be edited or added in the movie database, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function editActionPost(int $id = 0) : object
    {
        // Framework services
        $request = $this->app->request;
        $response = $this->app->response;
        $session = $this->app->session;

        $doSave = $request->getPost("doSave");
        $doDelete = $request->getPost("doDelete");
        $contentId = $request->getPost("contentId", $id);

        if ($doDelete === "delete") {
            return $response->redirect("content1/delete/{$contentId}");
        } elseif ($doSave === "save") {
            $postVariables = [
                "contentTitle",
                "contentPath",
                "contentSlug",
                "contentData",
                "contentType",
                "contentFilter",
                "contentPublish",
                "contentId"
            ];
            foreach ($postVariables as $val) {
                $params[$val] = $request->getPost($val);
            }
            if (!$params["contentSlug"]) {
                $params["contentSlug"] = slugify($params["contentTitle"]);
            }

            if (!$params["contentPath"]) {
                $params["contentPath"] = null;
            }

            $slugErrorMsg = $this->helper->updateRow($params, $contentId);
            if ($slugErrorMsg != "") {
                $session->set("slugErrorMsg", $slugErrorMsg);
                return $response->redirect("content1/edit/$contentId");
            }

            return $response->redirect("content1/edit/$contentId");
        } else {
            return $response->redirect("content1/admin");
        }
    }

    /**
     * This is the delete method GET action that shows all the content, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function deleteActionGet(int $id = 0) : object
    {
        // Framework services
        $page = $this->app->page;

        $title = "Delete content | oophp";

        $titleObj = $this->helper->getRowTitle($id);

        $page->add("content1/header");
        $page->add("content1/delete", [
            "contentId" => $id,
            "contentTitle" => $titleObj->title,
        ]);
        // $this->app->page->add("content1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the delete method POST action that shows all the content, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function deleteActionPost(int $id = 0) : object
    {
        // Framework services
        $request = $this->app->request;
        $response = $this->app->response;

        // $doDeleteStr = explode(" ", $request->getPost("doDelete"));
        $doDelete = $request->getPost("doDelete");

        if ($doDelete === "delete") {
            $this->helper->markRowDeleted($id);
        }
        return $response->redirect("content1/admin");
    }

    /**
     * This is the showPages method action that shows all the
     * pages in the content database, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function showPagesAction() : object
    {
        // Framework services
        $page = $this->app->page;

        $title = "View pages | oophp";

        $res = $this->helper->getAllPages();

        $page->add("content1/header");
        $page->add("content1/showPages", [
            "res" => $res,
        ]);
        // $this->app->page->add("content1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the showPage method action that shows the pages with
     * a specific path in the content database, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function showPageAction(string $path) : object
    {
        // Framework services
        $page = $this->app->page;

        $content = $this->helper->getOnePage($path);

        $page->add("content1/headerUpOneLevel");

        if (!$content) {
            header("HTTP/1.0 404 Not Found");
            $title = "404";
            $page->add("content1/404.php");
        } else {
            $title = $content->title;
            $page->add("content1/showPage", [
                "content" => $content,
                "parsedData" => parseText($this->textFilter, $content->data, $content->filter),
            ]);
        }
        // $this->app->page->add("content1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the showBlog method action that shows all the
     * blog posts in the content database, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function showBlogAction() : object
    {
        // Framework services
        $page = $this->app->page;

        $title = "View blog | oophp";

        $res = $this->helper->getAllPosts();

        $page->add("content1/header");
        $page->add("content1/showBlog", [
            "res" => $res,
        ]);
        // $this->app->page->add("content1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the showBlogPost method action that shows the blog post
     * with a specific slug in the content database, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function showBlogPostAction(string $slug) : object
    {
        // Framework services
        $page = $this->app->page;
    
        $content = $this->helper->getOnePost($slug);

        $page->add("content1/headerUpOneLevel");

        if (!$content) {
            header("HTTP/1.0 404 Not Found");
            $title = "404";
            $page->add("content1/404.php");
        } else {
            $title = $content->title;
            $page->add("content1/showBlogPost", [
                "content" => $content,
                "parsedData" => parseText($this->textFilter, $content->data, $content->filter),
            ]);
        }
        // $this->app->page->add("content1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }
}
