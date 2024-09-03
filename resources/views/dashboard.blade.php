<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            text-align: right;
        }
        .header a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
        }
        .container {
            padding: 20px;
        }
        .dashboard-heading {
            text-align: center;
            font-weight: bold;
            font-size: 2rem;
            margin-bottom: 30px;
            color: #333;
        }
        .card {
            border: none; /* Remove border */
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card-body {
            padding: 20px;
            color: #333;
        }
        .card-title {
            font-size: 1.25rem;
            color: #000;
        }
        .card-text {
            font-size: 1rem;
            color: #555;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
        .search-bar {
            max-width: 500px;
            margin: 0 auto;
        }
        .modal-header {
            border-bottom: 1px solid #dee2e6;
        }
        .modal-title {
            font-size: 1.5rem;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <span>Welcome, {{ Auth::user()->name }}</span>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
    <div class="container">
        <h1 class="dashboard-heading">Pixarsart Studio Bug Creator</h1>

        <!-- Search bar -->
        <div class="mb-4 search-bar">
            <input type="text" id="searchBar" class="form-control" placeholder="Search projects...">
        </div>

        <!-- Button to trigger modal -->
        <div class="text-center mb-4">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#projectModal">
                Add New Project
            </button>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="projectModal" tabindex="-1" role="dialog" aria-labelledby="projectModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="projectModalLabel">Add New Project</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="projectForm">
                            @csrf
                            <div class="form-group">
                                <label for="name">Project Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="details">Project Details</label>
                                <textarea class="form-control" id="details" name="details" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Project</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects cards -->
        <div id="projectsContainer" class="row">
            <!-- Project cards will be loaded here dynamically -->
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4" id="paginationContainer">
            <!-- Pagination will be loaded here dynamically -->
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- AJAX Script for form submission and project loading -->
    <script>
        $(document).ready(function() {
            // Function to handle form submission
            $('#projectForm').on('submit', function(event) {
                event.preventDefault(); // Prevent the form from submitting via the browser
                var formData = $(this).serialize(); // Serialize form data
                $.ajax({
                    url: '/projects', // The URL for your backend route
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#projectModal').modal('hide'); // Hide the modal
                        loadProjects(); // Reload the projects list
                    },
                    error: function(response) {
                        alert('An error occurred. Please try again.');
                    }
                });
            });

            // Function to generate a very light random color
            function getRandomLightColor() {
                var letters = '89ABCDEF';
                var color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 8)];
                }
                return color;
            }

            // Function to load projects with optional search query
            function loadProjects(query = '') {
                $.ajax({
                    url: '/projects', // The URL for your backend route to fetch projects
                    type: 'GET',
                    data: { search: query }, // Pass the search query
                    success: function(response) {
                        var projectsContainer = $('#projectsContainer');
                        var paginationContainer = $('#paginationContainer');
                        projectsContainer.empty(); // Clear existing projects
                        paginationContainer.empty(); // Clear existing pagination

                        response.projects.data.forEach(function(project) {
                            var randomColor = getRandomLightColor();
                            var projectCard = `
                                <div class="col-md-4 mb-3">
                                    <a href="/projects/${project.id}/bugs" class="text-decoration-none">
                                        <div class="card" style="background-color: ${randomColor};">
                                            <div class="card-body">
                                                <h5 class="card-title">${project.name}</h5>
                                                <p class="card-text">${project.details}</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            `;
                            projectsContainer.append(projectCard); // Append new project card
                        });

                        // Generate pagination
                        var paginationLinks = '';
                        if (response.projects.prev_page_url) {
                            paginationLinks += `<a class="page-link" href="#" data-url="${response.projects.prev_page_url}">Previous</a>`;
                        }
                        for (var i = 1; i <= response.projects.last_page; i++) {
                            paginationLinks += `<a class="page-link" href="#" data-url="${response.projects.path}?page=${i}">${i}</a>`;
                        }
                        if (response.projects.next_page_url) {
                            paginationLinks += `<a class="page-link" href="#" data-url="${response.projects.next_page_url}">Next</a>`;
                        }
                        paginationContainer.html(`<nav aria-label="Page navigation"><ul class="pagination">${paginationLinks}</ul></nav>`);
                    },
                    error: function(response) {
                        alert('An error occurred while loading projects.');
                    }
                });
            }

            // Initial load of projects
            loadProjects();

            // Handle pagination link clicks
            $(document).on('click', '#paginationContainer .page-link', function(event) {
                event.preventDefault();
                var url = $(this).data('url');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        var projectsContainer = $('#projectsContainer');
                        var paginationContainer = $('#paginationContainer');
                        projectsContainer.empty(); // Clear existing projects
                        paginationContainer.empty(); // Clear existing pagination

                        response.projects.data.forEach(function(project) {
                            var randomColor = getRandomLightColor();
                            var projectCard = `
                                <div class="col-md-4 mb-3">
                                    <a href="/projects/${project.id}/bugs" class="text-decoration-none">
                                        <div class="card" style="background-color: ${randomColor};">
                                            <div class="card-body">
                                                <h5 class="card-title">${project.name}</h5>
                                                <p class="card-text">${project.details}</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            `;
                            projectsContainer.append(projectCard); // Append new project card
                        });

                        // Generate pagination
                        var paginationLinks = '';
                        if (response.projects.prev_page_url) {
                            paginationLinks += `<a class="page-link" href="#" data-url="${response.projects.prev_page_url}">Previous</a>`;
                        }
                        for (var i = 1; i <= response.projects.last_page; i++) {
                            paginationLinks += `<a class="page-link" href="#" data-url="${response.projects.path}?page=${i}">${i}</a>`;
                        }
                        if (response.projects.next_page_url) {
                            paginationLinks += `<a class="page-link" href="#" data-url="${response.projects.next_page_url}">Next</a>`;
                        }
                        paginationContainer.html(`<nav aria-label="Page navigation"><ul class="pagination">${paginationLinks}</ul></nav>`);
                    },
                    error: function(response) {
                        alert('An error occurred while loading projects.');
                    }
                });
            });

            // Search functionality
            $('#searchBar').on('keyup', function() {
                var query = $(this).val();
                loadProjects(query); // Reload projects with search query
            });

            // Handle project card clicks to redirect to bug creation page
            $(document).on('click', '.card', function(event) {
                event.preventDefault();
                var url = $(this).closest('a').attr('href');
                window.location.href = url;
            });
        });
    </script>
</body>
</html>
