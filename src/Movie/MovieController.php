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
    * @var DatabaseHelper $helper a support class to help with the database
     */
    private $db;
    private $helper;



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
        $this->helper = new DatabaseHelper($this->db, "movie");
    }

    /**
     * This is the login method GET action that shows the login form, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function loginActionGet() : object
    {
        // Framework services
        $page = $this->app->page;
        $session = $this->app->session;

        $title = "Login to the database";

        $loginMessage = $session->get("loginMessage");
        $movieUser = $session->get("movieUser");

        $page->add("movie1/header", [
            "movieUser" => $movieUser,
        ]);

        $page->add("user/login", [
            "loginMessage" => $loginMessage,
        ]);

        // $page->add("movie1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the login method POST action that handles login, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function loginActionPost() : object
    {
        // Framework services
        $response = $this->app->response;
        $request = $this->app->request;
        $session = $this->app->session;

        $user = $request->getPost("user");
        $password = $request->getPost("password");

        if ($this->helper->valid($user, $password)) {
            $session->set("loginMessage", null);
            $session->set("movieUser", $user);
            return $response->redirect("movie1/showAll");
        } else {
            $session->set("loginMessage", "Faulty login, try again!");
            return $response->redirect("movie1/login");
        }
    }

    /**
     * This is the logout method GET action that shows the login form, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function logoutActionGet() : object
    {
        // Framework services
        $page = $this->app->page;
        $session = $this->app->session;

        $title = "Logout from the database";

        $movieUser = $session->get("movieUser");

        $page->add("movie1/header", [
            "movieUser" => $movieUser,
        ]);
        $page->add("user/logout");

        // $page->add("movie1/debug");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the logout method POST action that handles login, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function logoutActionPost() : object
    {
        // Framework services
        $response = $this->app->response;
        $session = $this->app->session;

        $session->set("movieUser", null);

        return $response->redirect("movie-db");
    }

    /**
     * This is the showAll method action that shows all the movies, it handles:
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
        $session = $this->app->session;

        $title = "Movie database | oophp";

        $res = $this->helper->getAllRows();

        $movieUser = $session->get("movieUser");

        $page->add("movie1/header", [
            "movieUser" => $movieUser,
        ]);
        $page->add("movie1/showAll", [
            "res" => $res,
        ]);
        // $this->app->page->add("movie1/debug");

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
        $movieUser = $session->get("movieUser");

        $page->add("movie1/header", [
            "movieUser" => $movieUser,
        ]);

        if ($reset) {
            $dbConfig = $this->app->configuration->load("database");
            $resetInfo = $this->helper->getCommand($dbConfig['config']);
            $page->add("movie1/reset", [
                "reset" => $reset,
                "command" => $resetInfo[0],
                "status" => $resetInfo[1],
                "output" => $resetInfo[2],
            ]);
        } else {
            $page->add("movie1/reset", [
                "reset" => null,
            ]);
        }

        // $this->app->page->add("movie1/debug");

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

        return $response->redirect("movie1/reset");
    }

    /**
     * This is the searchTitle method GET action that shows the titles
     * that was searched for in the movie database, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
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

        $movieUser = $session->get("movieUser");

        $page->add("movie1/header", [
            "movieUser" => $movieUser,
        ]);
        if ($doSearch) {
            $res = $this->helper->searchForTitle($searchTitle);

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
     * @return object
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
     * @return object
     */
    public function searchYearActionGet() : object
    {
        // Framework services
        $page = $this->app->page;
        $session = $this->app->session;
        $res = "";

        $title = "SELECT * WHERE year";

        $doSearch = $session->get("doSearch");
        $year1 = $session->get("year1", 0);
        $year2 = $session->get("year2", 0);
        $session->set("doSearch", null);
        $session->set("year1", null);
        $session->set("year2", null);

        $movieUser = $session->get("movieUser");

        $page->add("movie1/header", [
            "movieUser" => $movieUser,
        ]);
        if ($doSearch) {
            $res = $this->helper->searchForYear($year1, $year2);

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
     * @return object
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
     * This is the select method action that shows all the movies in raw
     * print, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function selectAction() : object
    {
        // Framework services
        $page = $this->app->page;
        $session = $this->app->session;

        $title = "Movie database | oophp";

        $res = $this->helper->getAllRows();

        $movieUser = $session->get("movieUser");

        $page->add("movie1/header", [
            "movieUser" => $movieUser,
        ]);
        $page->add("movie1/select", [
            "sql"=> "SELECT * FROM movie;",
            "res" => $res,
        ]);

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This is the movieSelect method GET action that shows the titles
     * that can be selected in the movie database, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
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

        $res = $this->helper->getRowsWithIdAndTitle();

        $movieUser = $session->get("movieUser");

        $page->add("movie1/header", [
            "movieUser" => $movieUser,
        ]);
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
     * @return object
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

        if ($doDelete) {
            $this->helper->deleteRow($movieId);
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
     * @return object
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
            $movieId = $this->helper->insertRowAndReturnLastId();
            $session->set("movieId", $movieId);
        }

        $res = $this->helper->getRow($movieId);

        $movieUser = $session->get("movieUser");

        $page->add("movie1/header", [
            "movieUser" => $movieUser,
        ]);
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
     * @return object
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
            $this->helper->updateRow($movieTitle, $movieYear, $movieImage, $movieId);
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

        $orderBy = $session->get("orderby", "id");
        $order = $session->get("order", "asc");

        $res = $this->helper->showSorted($orderBy, $order);

        $movieUser = $session->get("movieUser");

        $page->add("movie1/header", [
            "movieUser" => $movieUser,
        ]);
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
     * @return object
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

        // Get max number of pages
        $max = $this->helper->getMaxForPagination($hits);

        // Get current page
        $currentPage = $session->get("currentPage", 1);

        $orderBy = $session->get("orderby", "id");
        $order = $session->get("order", "asc");

        $res = $this->helper->showSortedAndPaginated($hits, $max, $currentPage, $orderBy, $order);

        $movieUser = $session->get("movieUser");

        $page->add("movie1/header", [
            "movieUser" => $movieUser,
        ]);
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
     * @return object
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
