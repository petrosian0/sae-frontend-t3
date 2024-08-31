@extends('layouts.app')

@section('title', 'Manage Events')

@section('content')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <div class="container mx-auto mt-4 px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Manage Events</h1>
        <button id="addEventBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow mb-4">
            Add Event
        </button>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full bg-white border-collapse border">
                <thead class="bg-gray-100 text-gray-800">
                    <tr>
                        <th class="py-3 px-4 border text-left">Title</th>
                        <th class="py-3 px-4 border text-left">Start Date</th>
                        <th class="py-3 px-4 border text-left">End Date</th>
                        <th class="py-3 px-4 border text-left">Actions</th>
                    </tr>
                </thead>
                <tbody id="eventsTableBody" class="text-gray-700">
                    <!-- Events will be dynamically loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for Events -->
    <div id="eventModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
                <div class="p-4 border-b">
                    <h2 id="eventModalTitle" class="text-xl font-semibold text-gray-800">Add/Edit Event</h2>
                </div>
                <form id="eventForm" class="p-4">
                    <input type="hidden" id="eventId" name="event_id">
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 font-medium mb-2">Title</label>
                        <input type="text" id="title" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="startDate" class="block text-gray-700 font-medium mb-2">Start Date</label>
                        <input type="datetime-local" id="startDate" name="start_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="endDate" class="block text-gray-700 font-medium mb-2">End Date</label>
                        <input type="datetime-local" id="endDate" name="end_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 font-medium mb-2">Description</label>
                        <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <input type="hidden" name="is_active" value="1">
                    <div class="flex justify-end border-t p-4">
                        <button id="closeEventModalBtn" type="button" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded shadow mr-2">
                            Close
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow">
                            Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Load events data
            fetchEvents();

            // Add Event Button Click
            document.getElementById('addEventBtn').addEventListener('click', function () {
                openEventModal(); // Open modal for adding new event
            });

            // Close Event Modal
            document.getElementById('closeEventModalBtn').addEventListener('click', function () {
                closeModal('eventModal');
            });

            // Handle Form Submission
            document.getElementById('eventForm').addEventListener('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                let formObject = {};
                formData.forEach((value, key) => (formObject[key] = value));

                let eventId = document.getElementById('eventId').value;
                let url = eventId ? `/manage-events/${eventId}` : '/manage-events';
                let method = eventId ? 'PUT' : 'POST';

                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formObject)
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.errors) {
                            alert('Validation failed: ' + JSON.stringify(data.errors));
                            return;
                        }
                        fetchEvents();
                        closeModal('eventModal');
                    })
                    .catch(error => {
                        alert('An error occurred: ' + (error.message || 'Unknown error'));
                    });
            });

            // Function to fetch events and display in the table
            function fetchEvents() {
                fetch('/manage-events/data')
                    .then(response => response.json())
                    .then(events => {
                        const tableBody = document.getElementById('eventsTableBody');
                        tableBody.innerHTML = '';
                        events.forEach(event => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="py-3 px-4 border">${event.title}</td>
                                <td class="py-3 px-4 border">${event.start}</td>
                                <td class="py-3 px-4 border">${event.end}</td>
                                <td class="py-3 px-4 border">
                                    <button onclick="editEvent(${event.id})" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-2 py-1 rounded">Edit</button>
                                    <button onclick="deleteEvent(${event.id})" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-2 py-1 rounded">Delete</button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    });
            }

            // Function to load event data into the modal for editing
            window.editEvent = function (id) {
                fetch(`/manage-events/${id}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch event data.');
                        }
                        return response.json();
                    })
                    .then(event => {
                        if (event.error) {
                            alert(event.error);
                            return;
                        }

                        // Log the received event data to check if it's correct
                        console.log('Fetched Event:', event);

                        // Check if the fields exist and are populated correctly
                        document.getElementById('eventId').value = event.id || '';
                        document.getElementById('title').value = event.title || '';
                        document.getElementById('startDate').value = event.start || '';
                        document.getElementById('endDate').value = event.end || '';
                        document.getElementById('description').value = event.description || '';

                        openEventModal();
                    })
                    .catch(error => {
                        console.error('Error fetching event:', error);
                        alert('An error occurred while fetching the event data.');
                    });
            };




            // Function to delete an event
            window.deleteEvent = function (id) {
                if (confirm('Are you sure you want to delete this event?')) {
                    fetch(`/manage-events/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    })
                        .then(() => fetchEvents())
                        .catch(error => {
                            console.error('Error deleting event:', error);
                            alert('An error occurred while deleting the event.');
                        });
                }
            };

            // Function to open the event modal and reset form
            function openEventModal() {
                document.getElementById('eventForm').reset();
                document.getElementById('eventModal').classList.remove('hidden');
            }

            // Function to close the modal
            function closeModal(modalId) {
                document.getElementById(modalId).classList.add('hidden');
            }
        });

    </script>
@endsection
