<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class ArrangePositions
{

    public static function run(int $id)
    {

        DB::update("
            WITH ranked_proposals AS (
                SELECT
                    id,
                    ROW_NUMBER() OVER (ORDER BY hours, created_at) AS p
                FROM
                    proposals
                WHERE
                    project_id = :project
            )

            UPDATE proposals
            SET position = (
                SELECT p
                FROM ranked_proposals
                WHERE ranked_proposals.id = proposals.id
            ) 
            WHERE project_id = :project
    ", ['project' => $id]);
    }
}
