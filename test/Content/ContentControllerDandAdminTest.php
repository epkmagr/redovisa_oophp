<?php

namespace Epkmagr\Content;

use Anax\Controller\SampleAppController;
use Anax\DI\DIMagic;
use PHPUnit\Framework\TestCase;
use Anax\Response\ResponseUtility;

/**
 * Test the controller like it would be used from the router,
 * simulating the actual router paths and calling it directly.
 */
class ContentControllerDandAdminTest extends TestCase
{
    /**
     * @var ContentController $controller   The ContentController to be tested.
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

        $this->controller = new ContentController();
        $this->controller->setApp($app);
        $this->controller->initialize();
    }

    /**
     * Call the controller admin action GET.
     */
    public function testAdminActionGet()
    {
        $res = $this->controller->adminActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller admin action POST with do delete.
     */
    public function testAdminActionPost()
    {
        $res = $this->controller->adminActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "content1/admin"));
    }

    /**
     * Call the controller admin action POST with do delete.
     */
    public function testAdminActionPostDoDelete()
    {
        $this->app->request->setGlobals([
            "post" => [
                "contentTitle" => "A title",
                "doDelete" => "delete 1",
            ]
        ]);
        $res = $this->controller->adminActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "content1/delete"));
    }

    /**
     * Call the controller admin action POST with do edit.
     */
    public function testAdminActionPostDoEdit()
    {
        $this->app->request->setGlobals([
            "post" => [
                "contentTitle" => "A title",
                "doEdit" => "edit 2",
            ]
        ]);
        $res = $this->controller->adminActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "content1/edit"));
    }

    /**
     * Call the controller delete action GET.
     */
    public function testDeleteActionGet()
    {
        $res = $this->controller->deleteActionGet(1);
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller delete action POST nothing set.
     */
    public function testDeleteActionPost()
    {
        $res = $this->controller->deleteActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "content1/admin"));
    }

    /**
     * Call the controller delete action POST do delete.
     */
    public function testDeleteActionPostDoDelete()
    {
        $this->app->request->setGlobals([
            "post" => [
                "contentTitle" => "A title",
                "doDelete" => "delete",
            ]
        ]);
        $res = $this->controller->deleteActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "content1/admin"));

        $this->resetDatabase();
    }

    /**
     * Help method to check if the object constains the location, if so
     * it returns true otherwise false.
     *
     * @return boolean as if location is found.
     */
    public function checkLocation($res, string $location)
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

    /**
     * Setup the controller, before each testcase, just like the router
     * would set it up.
     */
    private function resetDatabase(): void
    {
        // Reset the database after CRUD testcases
        $dbConfig = $this->app->configuration->load("database");
        $helper = new DatabaseHelper($this->app->db, "content");
        $helper->getCommand($dbConfig['config']);
    }
}
