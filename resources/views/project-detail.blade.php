<x-app-layout>
    @section('header', 'Project Details') <!-- Set header for this page -->
    <x-slot name="slot">
        <!-- Page content here -->
        <h2>{{ $project->name }}</h2>
        <p>{{ $project->details }}</p>
        <!-- Add more content or include components here -->
    </x-slot>
</x-app-layout>
