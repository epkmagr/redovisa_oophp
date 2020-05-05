<?php
namespace Epkmagr\Movie;

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
     * This is the method returns true if it is a valid user, false otherwise
     *
     * @return boolean
     */
    public function valid($user, $password) : bool
    {
        if ($user == null || $password == null) {
            return false;
        } else {
            $sql = "SELECT `user` FROM `users` WHERE `user`='$user' AND `password`=MD5('$password');";
            $res = $this->db->executeFetch($sql);
            if ($res->user == $user) {
                return true;
            } else {
                return false;
            }
        }
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
     * Returns the result from a search for a title
     *
     * @var string $searchTitle a title to search for in the database.
     * @return array a result set from search for a title
     */
    public function searchForTitle(string $searchTitle) : array
    {
        $sql = "SELECT * FROM $this->table WHERE title LIKE ?;";
        $res = $this->db->executeFetchAll($sql, [$searchTitle]);

        return $res;
    }

    /**
     * Returns the result from a search for a year or between years
     *
     * @var int $year1 a year to search for in the database.
     * @var int $year2 a year to search for in the database.
     * @return array a result set from search for a title
     */
    public function searchForYear(int $year1, int $year2) : array
    {
        if ($year1 && $year2) {
            $sql = "SELECT * FROM $this->table WHERE year >= ? AND year <= ?;";
            $res = $this->db->executeFetchAll($sql, [$year1, $year2]);
        } elseif ($year1) {
            $sql = "SELECT * FROM $this->table WHERE year >= ?;";
            $res = $this->db->executeFetchAll($sql, [$year1]);
        } elseif ($year2) {
            $sql = "SELECT * FROM $this->table WHERE year <= ?;";
            $res = $this->db->executeFetchAll($sql, [$year2]);
        }

        return $res;
    }

    /**
     * Returns the result from a select id and title
     *
     * @return array a result set from select *
     */
    public function getRowsWithIdAndTitle() : array
    {
        $sql = "SELECT id, title FROM $this->table;";
        $res = $this->db->executeFetchAll($sql);

        return $res;
    }

    /**
     * Deletes a row with id from the table
     *
     * @var int $id a title to search for in the database.
     * @return void
     */
    public function deleteRow(int $id)
    {
        if (is_numeric($id)) {
            $sql = "DELETE FROM $this->table WHERE id = ?;";
            $this->db->execute($sql, [$id]);
        }
    }

    /**
     * Inserts a row into the table and returns its id
     *
     * @return int as the id of the last inserted row
     */
    public function insertRowAndReturnLastId()
    {
        $sql = "INSERT INTO $this->table (title, year, image) VALUES (?, ?, ?);";
        $this->db->executeFetchAll($sql, ["A title", 2017, "img/noimage.png"]);
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
     * Updates a selected row with id with inf
     *
     * @var string $title the title for the row in the database.
     * @var int $year the year for the row in the database.
     * @var string $image the name of the image for the row in the database.
     * @var int $id the id for the row in the database.
     * @return void
     */
    public function updateRow(string $title, int $year, string $image, int $id)
    {
        $sql = "UPDATE $this->table SET title = ?, year = ?, image = ? WHERE id = ?;";
        $this->db->execute($sql, [$title, $year, $image, $id]);
    }

    /**
     * Returns the result from the database sorted in the way specified
     * by orderBy and order.
     *
     * @var string $orderBy the column in the database to be sorted after.
     * @var string $order the order asc or desc sort after.
     * @return array a result set
     */
    public function showSorted(string $orderBy, string $order) : array
    {
        // Only these values are valid
        $columns = ["id", "title", "year", "image"];
        $orders = ["asc", "desc"];

        // Incoming matches valid value sets
        if (!in_array($orderBy, $columns)) {
            $orderBy = "id";
        };
        if (!in_array($order, $orders)) {
            $order = "asc";
        };

        $sql = "SELECT * FROM $this->table ORDER BY $orderBy $order;";
        $res = $this->db->executeFetchAll($sql);

        return $res;
    }

    /**
     * Returns the result from a selected row with id
     *
     * @var int $hits the number of hits per page.
     * @return float a max number of pages is returned.
     */
    public function getMaxForPagination(int $hits) : float
    {
        $sql = "SELECT COUNT(id) AS max FROM $this->table;";
        $max = $this->db->executeFetchAll($sql);
        $max = ceil($max[0]->max / $hits);

        return $max;
    }

    /**
     * Returns the result from the database sorted in the way specified
     * by orderBy and order. The result is paginated by hits and currentPage.
     *
     * @var int $hits the number of hits per page.
     * @var int $max the max number of pages.
     * @var int $currentPage the current page.
     * @var string $orderBy the column in the database to be sorted after.
     * @var string $order the order asc or desc sort after.
     * @return array a result set
     */
    public function showSortedAndPaginated(int $hits, int $max, int $currentPage, string $orderBy, string $order) : array
    {
        if (!(is_numeric($hits) && $hits > 0 && $hits <= 8)) {
            $hits = 4;
        }

        if (!(is_numeric($hits) && $currentPage > 0 && $currentPage <= $max)) {
            $currentPage = 1;
        }
        $offset = $hits * ($currentPage - 1);

        // Only these values are valid
        $columns = ["id", "title", "year", "image"];
        $orders = ["asc", "desc"];

        // Incoming matches valid value sets
        if (!in_array($orderBy, $columns)) {
            $orderBy = "id";
        }
        if (!in_array($order, $orders)) {
            $order = "asc";
        }

        $sql = "SELECT * FROM $this->table ORDER BY $orderBy $order LIMIT $hits OFFSET $offset;";
        $res = $this->db->executeFetchAll($sql);

        return $res;
    }
}
