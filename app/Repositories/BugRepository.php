<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BugRepositoryInterface;
use App\Models\Bug;

class BugRepository implements BugRepositoryInterface
{
    public function getAllBugsByProject($projectId)
    {
        return Bug::where('project_id', $projectId)->get();
    }

    public function createBug(array $data)
    {
        return Bug::create($data);
    }

    public function updateBugStatus($id, $status)
    {
        $bug = Bug::find($id);
        if ($bug) {
            $bug->status = $status;
            $bug->save();
        }
        return $bug;
    }
}
