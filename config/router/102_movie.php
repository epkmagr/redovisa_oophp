<?php
/**
 * Movie database controlller.
 */
return [
    // All routes in order
    "routes" => [
        [
            "info" => "Movie database.",
            "mount" => "movie1",
            "handler" => "\Epkmagr\Movie\MovieController",
        ],
    ]
];
