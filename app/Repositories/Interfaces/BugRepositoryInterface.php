<?php

namespace App\Repositories\Interfaces;

interface BugRepositoryInterface
{
    public function getAllBugsByProject($projectId);

    public function createBug(array $data);

    public function updateBugStatus($id, $status);
}
