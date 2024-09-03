<!DOCTYPE html>
<html>
<head>
    <title>Bug Creation</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .card-body {
            padding: 20px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4 text-center font-weight-bold">Project Details</h1>
    
        <!-- Display project details -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">{{ $project->name ?? 'Project' }}</h5>
            </div>
            <div class="card-body">
                <p class="card-text">{{ $project->details ?? 'No details available.' }}</p>
                <!-- Button to trigger bug creation modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#bugModal">
                    Create the Bug
                </button>
            </div>
        </div>

        <!-- Bug creation modal -->
        <div class="modal fade" id="bugModal" tabindex="-1" role="dialog" aria-labelledby="bugModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bugModalLabel">Create a New Bug</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="bugForm">
                            @csrf
                            <input type="hidden" id="project_id" name="project_id" value="{{ $projectId }}">
                            <input type="hidden" id="bug_id" name="bug_id">
                            <div class="form-group">
                                <label for="title">Bug Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Bug Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Bug</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bugs table -->
        <div class="mt-4">
            <h2>Bugs for this Project</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="bugsTableBody">
                    @foreach($bugs as $bug)
                    <tr id="bug-{{ $bug->id }}">
                        <td>{{ $bug->title }}</td>
                        <td>{{ $bug->description }}</td>
                        <td>{{ $bug->status }}</td>
                        <td>
                            <button class="btn btn-secondary btn-sm edit-btn" data-id="{{ $bug->id }}">Edit</button>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $bug->id }}">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- AJAX Script for bug creation, update, and deletion -->
    <script>
$(document).ready(function() {
    // Handle form submission for creating or updating a bug
    $('#bugForm').on('submit', function(event) {
        event.preventDefault(); // Prevent default form submission
        var formData = $(this).serialize(); // Serialize form data
        var action = $(this).attr('action'); // Get the form action

        $.ajax({
            url: action, // URL based on form action
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
            },
            success: function(response) {
                if (response.success) {
                    $('#bugModal').modal('hide'); // Hide the modal
                    if ($('#bug_id').val()) {
                        updateBugInTable(response.bug); // Update existing bug
                    } else {
                        addBugToTable(response.bug); // Add new bug
                    }
                } else {
                    alert('Error: ' + (response.message || 'Unknown error occurred.'));
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', xhr.responseText);
                alert('An error occurred while saving the bug. Please try again.');
            }
        });
    });

    // Function to add a new bug to the table
    function addBugToTable(bug) {
        var bugRow = `
            <tr id="bug-${bug.id}">
                <td>${bug.title}</td>
                <td>${bug.description}</td>
                <td>${bug.status}</td>
                <td>
                    <button class="btn btn-secondary btn-sm edit-btn" data-id="${bug.id}">Edit</button>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="${bug.id}">Delete</button>
                </td>
            </tr>
        `;
        $('#bugsTableBody').append(bugRow); // Append new bug row
    }

    // Function to update a bug in the table
    function updateBugInTable(bug) {
        var row = $('#bug-' + bug.id);
        row.find('td').eq(0).text(bug.title);
        row.find('td').eq(1).text(bug.description);
        row.find('td').eq(2).text(bug.status);
    }

    // Handle click event for delete buttons
    $(document).on('click', '.delete-btn', function() {
        var bugId = $(this).data('id');
        if (confirm('Are you sure you want to delete this bug?')) {
            $.ajax({
                url: '/bugs/' + bugId, // Ensure this matches your route definition
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                },
                success: function(response) {
                    if (response.success) {
                        $('#bug-' + bugId).remove(); // Remove the bug row from the table
                    } else {
                        alert('Error: ' + (response.message || 'Unknown error occurred.'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr.responseText);
                    alert('An error occurred while deleting the bug. Please try again.');
                }
            });
        }
    });

    // Handle click event for edit buttons
    $(document).on('click', '.edit-btn', function() {
        var bugId = $(this).data('id');
        var row = $('#bug-' + bugId);
        var title = row.find('td').eq(0).text();
        var description = row.find('td').eq(1).text();
        var status = row.find('td').eq(2).text();

        $('#bugModal').modal('show');
        $('#title').val(title);
        $('#description').val(description);
        $('#status').val(status);
        $('#bug_id').val(bugId); // Set the bug ID in the hidden field
        $('#bugForm').attr('action', '/bugs/' + bugId); // Set the form action for the update request
    });
});




    </script>
</body>
</html>
