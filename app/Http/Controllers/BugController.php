<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\BugRepositoryInterface;
use App\Models\Project;

class BugController extends Controller
{
    protected $bugRepository;

    public function __construct(BugRepositoryInterface $bugRepository)
    {
        $this->bugRepository = $bugRepository;
    }

    public function index($projectId)
    {
        $bugs = $this->bugRepository->getAllBugsByProject($projectId);
        return view('bug_created', compact('bugs', 'projectId'));
    }

    public function store(Request $request, $projectId)
    {
        try {
            // Validate incoming request data
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|string|in:pending,in_progress,closed',
            ]);

            // Retrieve the project by ID or fail
            $project = Project::findOrFail($projectId);

            // Create the bug
            $bug = $project->bugs()->create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'] ?? '',
                'status' => $validatedData['status'],
            ]);

            // Return success response
            return response()->json(
                [
                    'success' => true,
                    'bug' => $bug,
                ],
                201
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Log and return project not found response
            \Log::error('Project not found: ' . $e->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Project not found.',
                ],
                404
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors and return validation response
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Validation errors occurred.',
                    'errors' => $e->errors(),
                ],
                422
            );
        } catch (\Exception $e) {
            // Log and return a generic error response
            \Log::error('Error creating bug: ' . $e->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => 'An error occurred while creating the bug.',
                ],
                500
            );
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,closed',
        ]);

        $bug = $this->bugRepository->updateBugStatus($id, $request->status);

        if ($bug) {
            return redirect()
                ->back()
                ->with('success', 'Bug status updated successfully.');
        }

        return redirect()
            ->back()
            ->with('error', 'Bug not found.');
    }

    public function show($projectId)
    {
        $project = Project::find($projectId); // Fetch project details using the Project model
        $bugs = $this->bugRepository->getAllBugsByProject($projectId);
        return view('bug_created', [
            'project' => $project,
            'projectId' => $projectId,
            'bugs' => $bugs,
        ]);
    }

    public function destroy($id)
    {
        $bug = Bug::find($id);
        if (!$bug) {
            return response()->json(
                ['success' => false, 'message' => 'Bug not found.'],
                404
            );
        }

        $bug->delete();
        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $bug = Bug::find($id);
        if (!$bug) {
            return response()->json(
                ['success' => false, 'message' => 'Bug not found.'],
                404
            );
        }

        $bug->title = $request->input('title');
        $bug->description = $request->input('description');
        $bug->status = $request->input('status');
        $bug->save();

        return response()->json(['success' => true, 'bug' => $bug]);
    }
}
