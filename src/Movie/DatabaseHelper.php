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
     */
    private $db;

    /**
     * Constructor to initiate the support class.
     *
     * @param string $db pointing out the database
     */
    public function __construct(string $db)
    {
        $this->db = $db;
    }
}
