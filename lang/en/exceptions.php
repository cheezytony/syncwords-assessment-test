<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    "events" => [
        "end_date_precedes_start_date" => "The *end date* must always be after the *start date*",
        "end_date_exceeds_limit" => "The *end date* cannot be more than 12 hours after the *start date*",
        "policies" => [
            "view" => "You are not allowed to access this event",
            "update" => "You are not allowed to modify this event",
            "delete" => "You are not allowed to remove this event",
        ]
    ]

];
