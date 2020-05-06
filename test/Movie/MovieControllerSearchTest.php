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
class MovieControllerSearchTest extends TestCase
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

        // $di->db->setOptions($this->mysqlOptions);
        $app = $di;
        $this->app = $app;
        $di->set("app", $app);

        // Create and initiate the controller
        $this->controller = new MovieController();
        $this->controller->setApp($app);
        $this->controller->initialize();
    }

    /**
     * Call the controller searchTitle action GET.
     */
    public function testSearchTitleActionGet()
    {
        $res = $this->controller->searchTitleActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller searchTitle action GET with search.
     */
    public function testSearchTitleActionGetWithSearch()
    {
        $this->app->session->set("doSearch", "Search");
        $this->app->session->set("searchTitle", "%mer%");

        $res = $this->controller->searchTitleActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller searchTitle action POST.
     */
    public function testSearchTitleActionPost()
    {
        $this->app->request->setGlobals([
            "post" => [
                "doSearch" => "Search",
                "searchTitle" => "%mer%",
            ]
        ]);
        $res = $this->controller->searchTitleActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "movie1/searchTitle"));
        $doSearch = $this->app->session->get("doSearch");
        $exp = "Search";
        $this->assertEquals($exp, $doSearch);
        $searchTitle = $this->app->session->get("searchTitle");
        $exp = "%mer%";
        $this->assertEquals($exp, $searchTitle);
    }

    /**
    * Call the controller searchYear action GET.
    */
    public function testSearchYearActionGet()
    {
        $this->app->session->set("doSearch", null);

        $res = $this->controller->searchYearActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
    * Call the controller searchYear action GET with do search
    * and year1.
    */
    public function testSearchYearActionGetSearchYear1()
    {
        $this->app->session->set("doSearch", "Search");
        $this->app->session->set("year1", "1997");

        $res = $this->controller->searchYearActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $doSearch = $this->app->session->get("doSearch");
        $this->assertNull($doSearch);
        $year1 = $this->app->session->get("year1");
        $this->assertNull($year1);
    }

    /**
    * Call the controller searchYear action GET with do search
    * and year2.
    */
    public function testSearchYearActionGetSearchYear2()
    {
        $this->app->session->set("doSearch", "Search");
        $this->app->session->set("year2", "2004");

        $res = $this->controller->searchYearActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $doSearch = $this->app->session->get("doSearch");
        $this->assertNull($doSearch);
        $year2 = $this->app->session->get("year2");
        $this->assertNull($year2);
    }

    /**
    * Call the controller searchYear action GET with do search
    * and both year1 and year2.
    */
    public function testSearchYearActionGetSearchYear1AndYear2()
    {
        $this->app->session->set("doSearch", "Search");
        $this->app->session->set("year1", "1997");
        $this->app->session->set("year2", "2004");

        $res = $this->controller->searchYearActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $doSearch = $this->app->session->get("doSearch");
        $this->assertNull($doSearch);
        $year1 = $this->app->session->get("year1");
        $this->assertNull($year1);
        $year2 = $this->app->session->get("year2");
        $this->assertNull($year2);
    }

    /**
    * Call the controller searchYear action POST.
    */
    public function testSearchYearActionPost()
    {
        $this->app->request->setGlobals([
           "post" => [
               "doSearch" => "Search",
               "year1" => "1997",
               "year2" => "2003",
           ]
        ]);
        $res = $this->controller->searchYearActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "movie1/searchYear"));
        $doSearch = $this->app->session->get("doSearch");
        $exp = "Search";
        $this->assertEquals($exp, $doSearch);
        $searchYear = $this->app->session->get("year1");
        $exp = "1997";
        $this->assertEquals($exp, $searchYear);
        $searchYear = $this->app->session->get("year2");
        $exp = "2003";
        $this->assertEquals($exp, $searchYear);
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
