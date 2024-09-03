<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // Method to display a listing of the resource with optional search
    public function index(Request $request)
    {
        // Get the search query from the request
        $search = $request->input('search', '');

        // Query to fetch projects with optional search filter
        $projects = Project::where('name', 'like', "%$search%")
                            ->orWhere('details', 'like', "%$search%")
                            ->paginate(6); // Adjust the pagination size as needed

        // Return the projects and pagination details as JSON
        return response()->json([
            'projects' => $projects
        ]);
    }

    // Method to store a newly created resource in storage
    public function store(Request $request, $projectId)
    {
        try {
            // Validate incoming request
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|string|in:pending,in_progress,closed',
            ]);
    
            // Retrieve project by ID
            $project = Project::findOrFail($projectId);
    
            // Create the bug
            $bug = $project->bugs()->create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'] ?? '',
                'status' => $validatedData['status'],
            ]);
    
            // Respond with success and created bug data
            return response()->json([
                'success' => true,
                'bug' => $bug,
            ], 201);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Log specific error and return 404 if project is not found
            \Log::error('Project not found: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Project not found.',
            ], 404);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors and return validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $e->errors(),
            ], 422);
    
        } catch (\Exception $e) {
            // Log generic errors and return 500 response
            \Log::error('Error creating bug: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the bug.',
            ], 500);
        }
    }
    

    // Method to show the specified resource
    public function show($id)
    {
        // Find project by ID
        $project = Project::findOrFail($id);

        // Return the project as JSON
        return response()->json([
            'project' => $project
        ]);
    }

    // Method to update the specified resource in storage
    public function update(Request $request, $id)
    {
        // Validate request data
        $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'required|string',
        ]);

        // Find project by ID
        $project = Project::findOrFail($id);

        // Update project details
        $project->update([
            'name' => $request->input('name'),
            'details' => $request->input('details'),
        ]);

        // Return the updated project as JSON
        return response()->json([
            'project' => $project
        ]);
    }

    // Method to remove the specified resource from storage
    public function destroy($id)
    {
        // Find project by ID
        $project = Project::findOrFail($id);

        // Delete the project
        $project->delete();

        // Return a success response
        return response()->json([
            'message' => 'Project deleted successfully'
        ]);
    }
}
