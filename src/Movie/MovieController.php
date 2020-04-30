<?php

namespace Epkmagr\Movie;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 * The controller will be injected with $app if implementing the interface
 * AppInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class MovieController implements AppInjectableInterface
{
    use AppInjectableTrait;



    /**
     * @var string $db a sample member variable that gets initialised
     */
    private $db;



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
    }

    /**
     * This is the showAll method action that shows all the movies, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function showAllAction() : object
    {
        // Framework services
        $page = $this->app->page;

        $title = "Movie database | oophp";

        $sql = "SELECT * FROM movie;";
        $res = $this->db->executeFetchAll($sql);

        $page->add("movie1/header");
        $page->add("movie1/showAll", [
            "res" => $res,
        ]);

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
     * @return string
     */
    public function resetActionGet() : object
    {
        // Framework services
        $page = $this->app->page;
        $session = $this->app->session;

        $title = "Resetting the database";

        $reset = $session->get("reset");
        $session->set("reset", null);

        $dbConfig = $this->app->configuration->load("database");

        $page->add("movie1/header");
        $page->add("movie1/reset", [
            "reset" => $reset,
            "dbConfig" => $dbConfig['config'],
        ]);

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
     * @return string
     */
    public function resetActionPost() : object
    {
        // Framework services
        $response = $this->app->response;
        $request = $this->app->request;
        $session = $this->app->session;

        $reset = $request->getPost("reset");
        $session->set("reset", $reset);

        return $response->redirect("movie1/reset");
    }

    /**
     * This is the select method action that shows all the movies in raw
     * print, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function selectAction() : object
    {
        // Framework services
        $page = $this->app->page;

        $title = "Movie database | oophp";

        $sql = "SELECT * FROM movie;";
        $res = $this->db->executeFetchAll($sql);

        $page->add("movie1/header");
        $page->add("movie1/select", [
            "sql"=> $sql,
            "res" => $res,
        ]);

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the searchTitle method GET action that shows the titles
     * that was searched for in the movie database, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function searchTitleActionGet() : object
    {
        // Framework services
        $page = $this->app->page;
        $session = $this->app->session;

        $title = "SELECT * WHERE title";

        $doSearch = $session->get("doSearch");
        $searchTitle = $session->get("searchTitle");
        $session->set("doSearch", null);
        $session->set("searchTitle", null);

        $page->add("movie1/header");
        if ($doSearch) {
            $sql = "SELECT * FROM movie WHERE title LIKE ?;";
            $res = $this->db->executeFetchAll($sql, [$searchTitle]);

            $page->add("movie1/showAll", [
                "res" => $res,
            ]);
        } else {
            $page->add("movie1/searchTitle", [
                "searchTitle" => $searchTitle,
            ]);
        }
        // $this->app->page->add("movie1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the searchTitle method POST action that searches for title
     * in the movie database, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function searchTitleActionPost() : object
    {
        // Framework services
        $response = $this->app->response;
        $request = $this->app->request;
        $session = $this->app->session;

        $doSearch = $request->getPost("doSearch");
        $session->set("doSearch", $doSearch);
        $searchTitle = $request->getPost("searchTitle");
        $session->set("searchTitle", $searchTitle);

        return $response->redirect("movie1/searchTitle");
    }

    /**
     * This is the searchYear method GET action that shows the movies
     * that matches the years you searched for in the movie database,
     * it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function searchYearActionGet() : object
    {
        // Framework services
        $page = $this->app->page;
        $session = $this->app->session;
        $res = "";

        $title = "SELECT * WHERE year";

        $doSearch = $session->get("doSearch");
        $year1 = $session->get("year1");
        $year2 = $session->get("year2");
        $session->set("doSearch", null);
        $session->set("year1", null);
        $session->set("year2", null);

        $page->add("movie1/header");
        if ($doSearch) {
            if ($year1 && $year2) {
                $sql = "SELECT * FROM movie WHERE year >= ? AND year <= ?;";
                $res = $this->db->executeFetchAll($sql, [$year1, $year2]);
            } elseif ($year1) {
                $sql = "SELECT * FROM movie WHERE year >= ?;";
                $res = $this->db->executeFetchAll($sql, [$year1]);
            } elseif ($year2) {
                $sql = "SELECT * FROM movie WHERE year <= ?;";
                $res = $this->db->executeFetchAll($sql, [$year2]);
            }

            $page->add("movie1/showAll", [
                "res" => $res,
            ]);
        } else {
            $page->add("movie1/searchYear", [
                "year1" => $year1,
                "year2" => $year2,
            ]);
        }
        // $this->app->page->add("movie1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the searchYear method POST action that searches for year
     * in the movie database, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function searchYearActionPost() : object
    {
        // Framework services
        $response = $this->app->response;
        $request = $this->app->request;
        $session = $this->app->session;

        $doSearch = $request->getPost("doSearch");
        $session->set("doSearch", $doSearch);
        $year1 = $request->getPost("year1");
        $session->set("year1", $year1);
        $year2 = $request->getPost("year2");
        $session->set("year2", $year2);

        return $response->redirect("movie1/searchYear");
    }
}
