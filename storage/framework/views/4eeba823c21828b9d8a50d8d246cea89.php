<?php $__env->startSection('title', 'Calendar'); ?>

<?php $__env->startSection('content'); ?>
    <style>
        /* Ensures that the modal is on top */
        #CalendarDetail {
            z-index: 9999;
        }
    </style>
    <div id="calendar" class="w-1/2 mx-auto mt-7"></div>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            if (!calendarEl) {
                console.error('Element with ID "calendar" is missing.');
                return;
            }

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                editable: true,
                events: function(fetchInfo, successCallback, failureCallback) {
                    fetch('/events')
                        .then(response => response.json())
                        .then(data => successCallback(data))
                        .catch(error => failureCallback(error));
                },
                eventClick: function(info) {
                    document.getElementById('eventId').value = info.event.id || '';
                    document.getElementById('title').value = info.event.title || '';
                    document.getElementById('startDate').value = info.event.start ? info.event.start.toISOString().slice(0, 16) : '';
                    document.getElementById('endDate').value = info.event.end ? info.event.end.toISOString().slice(0, 16) : '';
                    document.getElementById('description').value = info.event.extendedProps.description || '';

                    const modal = document.getElementById('CalendarDetail');
                    if (modal) {
                        modal.classList.remove('hidden');
                    } else {
                        console.error('Modal element with ID "CalendarDetail" is missing.');
                    }
                },
                dateClick: function(info) {
                    document.getElementById('eventId').value = '';
                    document.getElementById('title').value = '';
                    document.getElementById('startDate').value = `${info.dateStr}T12:00`;
                    document.getElementById('endDate').value = `${info.dateStr}T12:00`;
                    document.getElementById('description').value = '';

                    const modal = document.getElementById('CalendarDetail');
                    if (modal) {
                        modal.classList.remove('hidden');
                    } else {
                        console.error('Modal element with ID "CalendarDetail" is missing.');
                    }
                }
            });

            calendar.render();

            // Handle form submission
            document.getElementById('eventForm').addEventListener('submit', function(e) {
                e.preventDefault();

                // Create an object from form data
                let formData = new FormData(this);
                let formObject = {};
                formData.forEach((value, key) => (formObject[key] = value));

                // Logging form data to ensure values are correctly set
                console.log('Form Data:', formObject);

                let eventId = document.getElementById('eventId').value;
                let url = eventId ? `/events/${eventId}` : '/events';
                let method = eventId ? 'PUT' : 'POST';

                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formObject)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.errors) {
                        console.error('Validation Errors:', data.errors);
                        alert('Validation failed: ' + JSON.stringify(data.errors));
                        return;
                    }
                    if (data.error) {
                        throw new Error(data.message || 'Unknown error');
                    }
                    console.log('Server Response:', data);
                    closeModal();
                    calendar.refetchEvents();
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    alert('An error occurred while saving the event: ' + (error.message || 'Unknown error'));
                });
            });

            // Handle modal close
            document.getElementById('closeModalBtn').addEventListener('click', function() {
                closeModal();
            });

            // Handle event deletion
            document.getElementById('deleteEventBtn').addEventListener('click', function() {
                let eventId = document.getElementById('eventId').value;
                if (!eventId) {
                    alert('No event selected to delete.');
                    return;
                }

                fetch(`/events/${eventId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to delete event.');
                    }
                    closeModal();
                    calendar.refetchEvents();
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    alert('An error occurred while deleting the event: ' + (error.message || 'Unknown error'));
                });
            });

            // Function to close the modal
            function closeModal() {
                const modal = document.getElementById('CalendarDetail');
                if (modal) {
                    modal.classList.add('hidden');
                }
            }
        });
    </script>

    <!-- Modal -->
    <div id="CalendarDetail" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
                <div class="p-4 border-b">
                    <h2 class="text-xl font-semibold">Event Details</h2>
                </div>
                <form id="eventForm" class="p-4">
                    <input type="hidden" id="eventId" name="event_id">
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 font-medium mb-2">Title</label>
                        <input type="text" id="title" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="mb-4">
                        <label for="startDate" class="block text-gray-700 font-medium mb-2">Start Date and Time</label>
                        <input type="datetime-local" id="startDate" name="start_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="endDate" class="block text-gray-700 font-medium mb-2">End Date and Time</label>
                        <input type="datetime-local" id="endDate" name="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 font-medium mb-2">Description</label>
                        <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
                    </div>
                    <input type="hidden" name="is_active" value="1">
                    <div class="flex border-t p-4">
                        <button id="closeModalBtn" type="button" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">
                            Close
                        </button>
                        <button id="deleteEventBtn" type="button" class="bg-red-500 text-white px-4 py-2 rounded">
                            Delete
                        </button>
                        <button type="submit" class="ml-auto bg-blue-500 text-white px-4 py-2 rounded justify-end">
                            Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/petar/Desktop/SAE_FINAL_BACKEND/sites/event_management/resources/views/calendar.blade.php ENDPATH**/ ?>