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

    /**
     * This is the movieSelect method GET action that shows the titles
     * that can be selected in the movie database, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function movieSelectActionGet() : object
    {
        // Framework services
        $page = $this->app->page;
        $response = $this->app->response;
        $session = $this->app->session;

        $title = "Select a movie";

        $movieId = $session->get("movieId");
        $doAdd = $session->get("doAdd");
        $doEdit = $session->get("doEdit");

        $sql = "SELECT id, title FROM movie;";
        $res = $this->db->executeFetchAll($sql);

        $page->add("movie1/header");
        if ($doAdd || ($doEdit && is_numeric($movieId))) {
            return $response->redirect("movie1/movieEdit");
        } else {
            $page->add("movie1/movieSelect", [
                "movies" => $res,
            ]);
        }
        // $this->app->page->add("movie1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the movieSelect method POST action that selects a movie
     * in the movie database, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function movieSelectActionPost() : object
    {
        // Framework services
        $response = $this->app->response;
        $request = $this->app->request;
        $session = $this->app->session;

        $movieId = $request->getPost("movieId");
        $session->set("movieId", $movieId);
        $doDelete = $request->getPost("doDelete");
        $doAdd = $request->getPost("doAdd");
        $doEdit = $request->getPost("doEdit");
        $session->set("doAdd", $doAdd);
        $session->set("doEdit", $doEdit);

        if ($doDelete && is_numeric($movieId)) {
            $sql = "DELETE FROM movie WHERE id = ?;";
            $this->db->execute($sql, [$movieId]);
        }

        return $response->redirect("movie1/movieSelect");
    }

    /**
     * This is the movieEdit method GET action that shows the movie
     * that can be edited or added in the movie database, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function movieEditActionGet() : object
    {
        // Framework services
        $page = $this->app->page;
        $session = $this->app->session;

        $title = "UPDATE movie";

        $movieId = $session->get("movieId");
        $doAdd = $session->get("doAdd");
        $session->set("doAdd", null);
        $session->set("doEdit", null);

        if ($doAdd) {
            $sql = "INSERT INTO movie (title, year, image) VALUES (?, ?, ?);";
            $res = $this->db->executeFetchAll($sql, ["A title", 2017, "img/noimage.png"]);
            $movieId = $this->db->lastInsertId();
            $session->set("movieId", $movieId);
        }

        $sql = "SELECT * FROM movie WHERE id = ?;";
        $res = $this->db->executeFetch($sql, [$movieId]);

        $page->add("movie1/header");
        $page->add("movie1/movieEdit", [
            "movie" => $res,
            "movieId" => $movieId,
        ]);
        // $this->app->page->add("movie1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the movieEdit method POST action that posts the data of a movie
     * that has been edited or added in the movie database, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function movieEditActionPost() : object
    {
        // Framework services
        $response = $this->app->response;
        $request = $this->app->request;
        $session = $this->app->session;

        $movieId = $session->get("movieId");
        $movieTitle = $request->getPost("movieTitle");
        $movieYear = $request->getPost("movieYear");
        $movieImage = $request->getPost("movieImage");
        $doSave = $request->getPost("doSave");

        if ($doSave && is_numeric($movieId)) {
            $sql = "UPDATE movie SET title = ?, year = ?, image = ? WHERE id = ?;";
            $this->db->execute($sql, [$movieTitle, $movieYear, $movieImage, $movieId]);
            $session->set("doEdit", null);
        }

        return $response->redirect("movie1/movieEdit");
    }

    /**
     * This is the showAllSort method action that shows all the movies and
     * sorts it in different ways, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function showAllSortActionGet() : object
    {
        // Framework services
        $page = $this->app->page;
        $session = $this->app->session;

        $title = "Show and sort all movies";

        // Only these values are valid
        $columns = ["id", "title", "year", "image"];
        $orders = ["asc", "desc"];

        $orderBy = $session->get("orderby", "id");
        $order = $session->get("order", "asc");

        // Incoming matches valid value sets
        if (!in_array($orderBy, $columns)) {
            $orderBy = "id";
        };
        if (!in_array($order, $orders)) {
            $order = "asc";
        };

        $sql = "SELECT * FROM movie ORDER BY $orderBy $order;";
        $res = $this->db->executeFetchAll($sql);

        $page->add("movie1/header");
        $page->add("movie1/showAllSort", [
            "res" => $res,
        ]);
        // $this->app->page->add("movie1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the showAllSort method POST action that posts the sort
     * order of the movies in the movie database, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function showAllSortActionPost() : object
    {
        // Framework services
        $response = $this->app->response;
        $request = $this->app->request;
        $session = $this->app->session;

        $orderStrs = explode(" ", $request->getPost("order"));
        $session->set("orderby", $orderStrs[0]);
        $session->set("order", $orderStrs[1]);

        return $response->redirect("movie1/showAllSort");
    }

    /**
     * This is the showAllPaginate method action that shows all the movies paginated
     * and sorts it in different ways, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function showAllPaginateActionGet() : object
    {
        // Framework services
        $page = $this->app->page;
        $session = $this->app->session;

        $title = "Show, paginate movies";

        // Get number of hits per page
        $hits = $session->get("hits", 4);
        if (!(is_numeric($hits) && $hits > 0 && $hits <= 8)) {
            $hits = 4;
        }

        // Get max number of pages
        $sql = "SELECT COUNT(id) AS max FROM movie;";
        $max = $this->db->executeFetchAll($sql);
        $max = ceil($max[0]->max / $hits);

        // Get current page
        $currentPage = $session->get("currentPage", 1);
        if (!(is_numeric($hits) && $currentPage > 0 && $currentPage <= $max)) {
            $currentPage = 1;
        }
        $offset = $hits * ($currentPage - 1);

        // Only these values are valid
        $columns = ["id", "title", "year", "image"];
        $orders = ["asc", "desc"];

        $orderBy = $session->get("orderby", "id");
        $order = $session->get("order", "asc");

        // Incoming matches valid value sets
        if (!in_array($orderBy, $columns)) {
            $orderBy = "id";
        }
        if (!in_array($order, $orders)) {
            $order = "asc";
        }

        $sql = "SELECT * FROM movie ORDER BY $orderBy $order LIMIT $hits OFFSET $offset;";
        $res = $this->db->executeFetchAll($sql);

        $page->add("movie1/header");
        $page->add("movie1/showAllPaginate", [
            "res" => $res,
            "max" => $max,
            "currentPage" => $currentPage,
        ]);
        // $this->app->page->add("movie1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the showAllPaginate method POST action that posts the sort
     * order of the movies in the movie database, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function showAllPaginateActionPost() : object
    {
        // Framework services
        $response = $this->app->response;
        $request = $this->app->request;
        $session = $this->app->session;

        $orderStrs = explode(" ", $request->getPost("order"));
        $session->set("orderby", $orderStrs[0]);
        $session->set("order", $orderStrs[1]);
        $currentPage = $request->getPost("currentPage");
        if ($currentPage != null) {
            $session->set("currentPage", $currentPage);
        }
        $hits = $request->getPost("hits");
        if ($hits != null) {
            $session->set("hits", $hits);
        }

        return $response->redirect("movie1/showAllPaginate");
    }

    /**
     * This is the method returns the number of the last inserted
     * item or row in the database.
     *
     * @return int
     */
    public function getLastInsertedId() : int
    {
        $sql = "SELECT COUNT(*) AS count FROM movie;";
        $res = $this->db->executeFetch($sql);
        $id = $res->count;

        return $id;
    }
}
