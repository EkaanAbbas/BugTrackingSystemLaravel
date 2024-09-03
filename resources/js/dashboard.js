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

    // Function to load projects
    function loadProjects() {
        $.ajax({
            url: '/projects', // The URL for your backend route to fetch projects
            type: 'GET',
            success: function(response) {
                var projectsContainer = $('#projectsContainer');
                var paginationContainer = $('#paginationContainer');
                projectsContainer.empty(); // Clear existing projects
                paginationContainer.empty(); // Clear existing pagination

                response.projects.data.forEach(function(project) {
                    var projectCard = `
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">${project.name}</h5>
                                    <p class="card-text">${project.details}</p>
                                    <button class="btn btn-danger btn-delete" data-id="${project.id}">Delete</button>
                                </div>
                            </div>
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
                    var projectCard = `
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">${project.name}</h5>
                                    <p class="card-text">${project.details}</p>
                                    <button class="btn btn-danger btn-delete" data-id="${project.id}">Delete</button>
                                </div>
                            </div>
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

    // Handle delete button click
    $(document).on('click', '.btn-delete', function() {
        var projectId = $(this).data('id');
        if (confirm('Are you sure you want to delete this project?')) {
            $.ajax({
                url: '/projects/' + projectId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}' // Include CSRF token for security
                },
                success: function(response) {
                    loadProjects(); // Reload the projects list
                },
                error: function(xhr) {
                    var errorMessage = xhr.responseJSON?.error || 'An error occurred while deleting the project.';
                    alert(errorMessage);
                }
            });
        }
    });
});
