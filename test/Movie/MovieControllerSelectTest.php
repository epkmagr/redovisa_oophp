<?php

namespace Epkmagr\Movie;

use Anax\Controller\SampleAppController;
use Anax\DI\DIMagic;
use PHPUnit\Framework\TestCase;
use Anax\Response\ResponseUtility;

/**
 * Test the controller like it would be used from the router,
 * simulating the actual router paths and calling it directly.
 */
class MovieControllerSelectTest extends TestCase
{
    /**
     * @var MovieController $controller   The DiceController to be tested.
     * @var DIMagic  $app  The service container $di to contain $app as a service.
     */
    private $controller;
    private $app;

    /**
     * Setup the controller, before each testcase, just like the router
     * would set it up.
     */
    protected function setUp(): void
    {
        global $di;

        // Init service container $di to contain $app as a service
        $di = new DIMagic();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $app = $di;
        $this->app = $app;
        $di->set("app", $app);

        // Create and initiate the controller
        $this->controller = new MovieController();
        $this->controller->setApp($app);
        $this->controller->initialize();
    }

    /**
     * Call the controller movieSelect action GET.
     */
    public function testMovieSelectActionGet()
    {
        $this->app->session->set("movieId", "1");

        $res = $this->controller->movieSelectActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller movieSelect action GET with movieId set.
     */
    public function testMovieSelectActionGetMovieId()
    {
        $this->app->session->set("movieId", "1");

        $res = $this->controller->movieSelectActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $movieId = $this->app->session->get("movieId");
        $exp = "1";
        $this->assertEquals($exp, $movieId);
    }

    /**
     * Call the controller movieSelect action POST with do edit.
     */
    public function testMovieSelectActionGetDoEdit()
    {
        $this->app->session->set("movieId", 1);
        $this->app->session->set("doEdit", "Edit");

        $res = $this->controller->movieSelectActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "movie1/movieEdit"));
    }

    /**
     * Call the controller movieSelect action POST.
     */
    public function testMovieSelectActionPost()
    {
        $this->app->request->setGlobals([
            "post" => [
                "movieId" => 1,
            ]
        ]);
        $res = $this->controller->movieSelectActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "movie1/movieSelect"));
        $movieId = $this->app->session->get("movieId");
        $exp = 1;
        $this->assertEquals($exp, $movieId);
    }

    /**
     * Call the controller movieEdit action GET with movieId set.
     */
    public function testMovieEditActionGetMovieId()
    {
        $id = $this->controller->getLastInsertedId();
        $this->app->session->set("movieId", $id);
        $this->app->session->set("doAdd", "Add");

        $res = $this->controller->movieEditActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $movieId = $this->app->session->get("movieId");
        $exp = $id + 1;
        $this->assertEquals($exp, $movieId);
    }

    /**
     * Call the controller movieEdit action POST with saving new year.
     */
    public function testMovieEditActionPostDoSave()
    {
        $id = $this->controller->getLastInsertedId();
        $this->app->request->setGlobals([
            "post" => [
                "movieId" => $id,
                "movieTitle" => "New title",
                "movieYear" => 2020,
                "movieImage" => "img/newTitle.jpg",
                "doSave" => "Save",
            ]
        ]);
        $res = $this->controller->movieEditActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "movie1/movieEdit"));
    }

    /**
     * Call the controller movieSelect action POST.
     */
    public function testMovieSelectActionPostDoDelete()
    {
        $id = $this->controller->getLastInsertedId();
        $this->app->request->setGlobals([
            "post" => [
                "movieId" => $id,
                "doDelete" => "Delete",
            ]
        ]);
        $res = $this->controller->movieSelectActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "movie1/movieSelect"));
        $movieId = $this->controller->getLastInsertedId();
        $exp = $id - 1;
        $this->assertEquals($exp, $movieId);
    }

    /**
     * Call the controller movieEdit action POST.
     */
    public function testMovieEditActionPost()
    {
        $this->app->session->set("movieId", 1);
        $res = $this->controller->movieEditActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "movie1/movieEdit"));
        $movieId = $this->app->session->get("movieId");
        $exp = 1;
        $this->assertEquals($exp, $movieId);
    }



    /**
     * Help method to check if the object constains the location, if so
     * it returns true otherwise false.
     *
     * @return boolean as if location is found.
     */
    private function checkLocation($res, string $location)
    {
        // Check where to the redirect is (do you really need to assert this?)
        $headers = $res->getHeaders();
        $hasLocationHeader = false;
        foreach ($headers as $header) {
            if (substr($header, 0, 10) === "Location: ") {
                $hasLocationHeader = true;
                // The last part (a) is the url whereto redirect
                $this->assertContains($location, $header);
            }
        }
        return $hasLocationHeader;
    }
}
