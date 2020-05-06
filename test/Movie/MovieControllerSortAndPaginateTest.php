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
class MovieControllerSortAndPaginateTest extends TestCase
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
     * Call the controller showAllSort action GET.
     */
    public function testShowAllSortActionGet()
    {
        $res = $this->controller->showAllSortActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller showAllSort action POST.
     */
    public function testShowAllSortActionPost()
    {
        $this->app->request->setGlobals([
            "post" => [
                "order" => "title desc",
            ]
        ]);
        $res = $this->controller->showAllSortActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "movie1/showAllSort"));
        $orderBy = $this->app->session->get("orderby");
        $exp = "title";
        $this->assertEquals($exp, $orderBy);
    }

    /**
     * Call the controller showAllPaginate action GET.
     */
    public function testShowAllPaginateActionGet()
    {
        $res = $this->controller->showAllPaginateActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller showAllPaginate action POST.
     */
    public function testShowAllPaginateActionPost()
    {
        $this->app->request->setGlobals([
            "post" => [
                "order" => "title desc",
            ]
        ]);
        $res = $this->controller->showAllPaginateActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "movie1/showAllPaginate"));
        $orderBy = $this->app->session->get("orderby");
        $exp = "title";
        $this->assertEquals($exp, $orderBy);
    }

    /**
     * Call the controller showAllPaginate action POST with currentPage
     * and hits.
     */
    public function testShowAllPaginateActionPostCurrentPageAndHits()
    {
        $this->app->request->setGlobals([
            "post" => [
                "order" => "title desc",
                "currentPage" => 2,
                "hits" => 2,
            ]
        ]);
        $res = $this->controller->showAllPaginateActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "movie1/showAllPaginate"));
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
