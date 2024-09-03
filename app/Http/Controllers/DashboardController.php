<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch all projects with pagination
        $projects = Project::paginate(6); // Adjust the number for pagination as needed

        // Pass the projects to the view
        return view('dashboard', compact('projects'));
    }
}