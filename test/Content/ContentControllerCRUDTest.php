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
class ContentControllerCRUDTest extends TestCase
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
     * Call the controller create action GET.
     */
    public function testCreateActionGet()
    {
        $res = $this->controller->createActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller create action POST nothing set.
     */
    public function testCreateActionPost()
    {
        $res = $this->controller->createActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "content1/create"));
    }

    /**
     * Call the controller create action POST.
     */
    public function testCreateActionPostDoCreate()
    {
        $this->app->request->setGlobals([
            "post" => [
                "contentTitle" => "A title",
                "doCreate" => "create",
            ]
        ]);
        $res = $this->controller->createActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "content1/edit"));
    }

    /**
     * Call the controller edit action GET.
     */
    public function testEditActionGet()
    {
        $this->app->session->set("contentUser", "doe");
        
        $res = $this->controller->editActionGet(1);
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller edit action POST nothing set.
     */
    public function testEditActionPost()
    {
        $res = $this->controller->editActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "content1/admin"));
    }

    /**
     * Call the controller edit action POST with doSave.
     */
    public function testEditActionPostDoSave()
    {
        $this->app->request->setGlobals([
            "post" => [
                "contentId" => 1,
                "contentTitle" => "A title",
                "doSave" => "save",
            ]
        ]);
        $res = $this->controller->editActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "content1/edit"));
    }

    /**
     * Call the controller edit action POST with doSave and published.
     */
    public function testEditActionPostDoSavePublish()
    {
        $this->app->request->setGlobals([
            "post" => [
                "contentId" => 1,
                "contentTitle" => "A title",
                "doSave" => "save",
                "publish" => "publish",
            ]
        ]);
        $res = $this->controller->editActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "content1/edit"));
    }

    /**
     * Call the controller edit action POST with doDelete.
     */
    public function testEditActionPostDoDelete()
    {
        $this->app->request->setGlobals([
            "post" => [
                "contentId" => 1,
                "doDelete" => "delete",
            ]
        ]);
        $res = $this->controller->editActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
        $this->assertTrue($this->checkLocation($res, "content1/delete"));

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
