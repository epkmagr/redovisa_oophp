<?php
namespace Epkmagr\Content;

/**
 * A support class for MovieController
 *
 * @author Marie Grahn, mbfs17@student.bth.se
 */
class DatabaseHelper
{
    /**
    * @var string $db pointing out the database
    * @var string $table pointing out the table be used in the database
     */
    private $db;
    private $table;

    /**
     * Constructor to initiate the support class.
     *
     * @param string $db pointing out the database
     * @param string $table pointing out the table in the database
     */
    public function __construct($db, string $table)
    {
        $this->db = $db;
        $this->table = $table;
    }

    /**
     * Returns the result from a select *
     *
     * @return array a result set from select *
     */
    public function getAllRows() : array
    {
        $sql = "SELECT * FROM $this->table;";
        $res = $this->db->executeFetchAll($sql);

        return $res;
    }

    /**
     * Inserts a row into the table and returns its id
     *
     * @var string $title the title of the row in the database.
     * @return int as the id of the last inserted row
     */
    public function insertRowAndReturnLastId(string $title) : int
    {
        $sql = "INSERT INTO $this->table (title) VALUES (?);";
        $this->db->executeFetchAll($sql, [$title]);
        $id = $this->db->lastInsertId();

        return $id;
    }

    /**
     * Returns the result from a selected row with id
     *
     * @var int $id the id for the row in the database.
     * @return array a result set from select with the specified id
     */
    public function getRow(int $id) : object
    {
        $sql = "SELECT * FROM $this->table WHERE id = ?;";
        $res = $this->db->executeFetch($sql, [$id]);

        return $res;
    }

    /**
     * Returns the title from a selected row with id
     *
     * @var int $id the id for the row in the database.
     * @return array a result set from select with the specified id
     */
    public function getRowTitle(int $id) : object
    {
        $sql = "SELECT title FROM $this->table WHERE id = ?;";
        $res = $this->db->executeFetch($sql, [$id]);

        return $res;
    }

    /**
     * Updates a selected row with id with inf
     *
     * @var array $params an array of parameters to update.
     * @var int $id the id for the row in the database.
     * @return string with error message, empty otherwise
     */
    public function updateRow(array $params, int $id)
    {
        $msg = "";

        if ($params["contentFilter"]) {
            $params["contentFilter"] = implode(",", $params["contentFilter"]);
        }
        if ($params['contentSlug'] != null) {
            $sql = "SELECT id, slug FROM $this->table WHERE slug = ?;";
            $res = $this->db->executeFetch($sql, [$params['contentSlug']]);
            if ($res == null || $res->id === $id) {
                $sql = "UPDATE $this->table SET title=?, path=?, slug=?, data=?, type=?, filter=?, published=? WHERE id = ?;";
                $this->db->execute($sql, array_values($params));
            } else {
                $msg = "The slug <i>" . $params['contentSlug'] . "</i> is taken, choose another!";
            }
        } else {
            $sql = "UPDATE $this->table SET title=?, path=?, slug=?, data=?, type=?, filter=?, published=? WHERE id = ?;";
            $this->db->execute($sql, array_values($params));
        }
        return $msg;
    }

    /**
     * Mark the row deleted
     *
     * @var int $id the id for the row in the database.
     * @return void
     */
    public function markRowDeleted(int $id)
    {
        $sql = "UPDATE $this->table SET deleted=NOW() WHERE id=?;";
        $this->db->execute($sql, [$id]);
    }

    /**
     * Returns the result from a selected row with id
     *
     * @var object $dbConfig the database configuration information.
     * @var string $test if testing another path to the setup.sql file is needed.
     * @return array with command on 0, status on 1 and output on 2.
     */
    public function getCommand($dbConfig, $test = null) : array
    {
        $resetInfo = [];

        // Test if a testcase is calling this method to get rid of error message
        // when running make phpunit.
        $trace = debug_backtrace();
        // var_dump($trace[1]);
        if (isset($trace[1])) {
            if (strpos($trace[1]['function'], "resetDatabase") !== false ||
                strpos($trace[1]['file'], "test") !== false) {
                $test = "test";
            }
        }
        // Restore the database to its original settings
        if ($test === null) {
            $file = "../sql/{$this->table}/setup.sql";
        } else {
            $file = ANAX_INSTALL_PATH . "/sql/{$this->table}/setup.sql";
        }

        $mysql  = "mysql";
        $output = null;

        // Extract hostname and databasename from dsn
        $dsnDetail = [];
        preg_match("/mysql:host=(.+);dbname=([^;.]+)/", $dbConfig["dsn"], $dsnDetail);
        $host = $dsnDetail[1];
        $database = $dsnDetail[2];
        $login = $dbConfig["username"];
        $password = $dbConfig["password"];

        $command = "$mysql -h{$host} -u{$login} -p{$password} $database < $file 2>&1";
        $output = [];
        $status = null;
        exec($command, $output, $status);

        $resetInfo[0] = $command;
        $resetInfo[1] = $status;
        $resetInfo[2] = $output;

        return $resetInfo;
    }

    /**
     * Returns all the pages in the database
     *
     * @return array a result set of pages
     */
    public function getAllPages() : array
    {
        $sql = <<<EOD
SELECT
*,
CASE
WHEN (deleted <= NOW()) THEN "isDeleted"
WHEN (published <= NOW()) THEN "isPublished"
ELSE "notPublished"
END AS status
FROM $this->table
WHERE type=?
;
EOD;
        $res = $this->db->executeFetchAll($sql, ["page"]);

        return $res;
    }

    /**
     * Returns a page in the database with a specific path
     *
     * @param string $path the path of the page to get
     * @return object a result set of page with a specific path
     */
    public function getOnePage(string $path) : object
    {
        $sql = <<<EOD
SELECT
    *,
    DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%dT%TZ') AS modified_iso8601,
    DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%d') AS modified
FROM $this->table
WHERE
    path = ?
    AND type = ?
    AND (deleted IS NULL OR deleted > NOW())
    AND published <= NOW()
;
EOD;
        $res = $this->db->executeFetch($sql, [$path, "page"]);

        return $res;
    }

    /**
     * Returns all the blog posts in the database
     *
     * @return array a result set of blog posts
     */
    public function getAllPosts() : array
    {
        $sql = <<<EOD
SELECT
    *,
    DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%dT%TZ') AS published_iso8601,
    DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%d') AS published
FROM $this->table
WHERE type=?
ORDER BY published DESC
;
EOD;
        $res = $this->db->executeFetchAll($sql, ["post"]);

        return $res;
    }

    /**
     * Returns all the blog posts in the database
     *
     * @return object a result set of blog posts
     */
    public function getOnePost(string $slug) : object
    {
        $sql = <<<EOD
SELECT
    *,
    DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%dT%TZ') AS published_iso8601,
    DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%d') AS published
FROM $this->table
WHERE
    slug = ?
    AND type = ?
    AND (deleted IS NULL OR deleted > NOW())
    AND published <= NOW()
ORDER BY published DESC
;
EOD;
        $res = $this->db->executeFetch($sql, [$slug, "post"]);

        return $res;
    }
}
