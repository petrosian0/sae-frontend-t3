<?php $__env->startSection('title', 'Manage Roles'); ?>

<?php $__env->startSection('content'); ?>

    <script src="https://cdn.tailwindcss.com"></script>
    <div class="container mx-auto mt-4 px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Manage Roles</h1>
        <button id="addRoleBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow mb-4">
            Add Role
        </button>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full bg-white border-collapse border">
                <thead class="bg-gray-100 text-gray-800">
                    <tr>
                        <th class="py-3 px-4 border text-left">Role Name</th>
                        <th class="py-3 px-4 border text-left">Status</th>
                        <th class="py-3 px-4 border text-left">Actions</th>
                    </tr>
                </thead>
                <tbody id="rolesTableBody" class="text-gray-700">
                    <!-- Roles will be dynamically loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for Roles -->
    <div id="roleModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
                <div class="p-4 border-b">
                    <h2 id="roleModalTitle" class="text-xl font-semibold text-gray-800">Add/Edit Role</h2>
                </div>
                <form id="roleForm" class="p-4">
                    <input type="hidden" id="roleId" name="role_id">
                    <div class="mb-4">
                        <label for="role_name" class="block text-gray-700 font-medium mb-2">Role Name</label>
                        <input type="text" id="role_name" name="role_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="is_active" class="block text-gray-700 font-medium mb-2">Status</label>
                        <select id="is_active" name="is_active" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="flex justify-end border-t p-4">
                        <button id="closeRoleModalBtn" type="button" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded shadow mr-2">
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
            fetchRoles();

            // Open modal for adding a new role
            document.getElementById('addRoleBtn').addEventListener('click', function () {
                document.getElementById('roleForm').reset(); // Reset the form
                document.getElementById('roleId').value = ''; // Clear the hidden input for ID
                openRoleModal();
            });

            // Close the role modal
            document.getElementById('closeRoleModalBtn').addEventListener('click', function () {
                closeModal('roleModal');
            });

            // Handle form submission
            document.getElementById('roleForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const roleId = document.getElementById('roleId').value;
                const url = roleId ? `/roles/${roleId}` : '/roles';
                const method = roleId ? 'PUT' : 'POST';

                // Collect form data as JSON object
                const formData = {
                    role_name: document.getElementById('role_name').value,
                    is_active: parseInt(document.getElementById('is_active').value, 10),
                };

                console.log('Submitting:', formData); // Debug: Log the form data being sent

                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData),
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Response:', data); // Debug: Log the response from the server
                        if (data.errors) {
                            console.error('Validation Errors:', data.errors);
                            alert('Validation failed: ' + JSON.stringify(data.errors));
                            return;
                        }
                        if (data.error) {
                            throw new Error(data.error || 'Unknown error');
                        }
                        alert('Role added successfully.'); // Show success message
                        fetchRoles(); // Reload the roles list
                        closeModal('roleModal'); // Close the modal
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred: ' + error.message);
                    });
            });

            // Fetch roles and display them in the table
            function fetchRoles() {
                fetch('/roles/data')
                    .then(response => response.json())
                    .then(roles => {
                        const tableBody = document.getElementById('rolesTableBody');
                        tableBody.innerHTML = ''; // Clear current roles
                        roles.forEach(role => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="py-3 px-4 border">${role.role_name}</td>
                                <td class="py-3 px-4 border">${role.is_active ? 'Active' : 'Inactive'}</td>
                                <td class="py-3 px-4 border">
                                    <button onclick="editRole(${role.id})" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-2 py-1 rounded">Edit</button>
                                    <button onclick="deleteRole(${role.id})" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-2 py-1 rounded">Delete</button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching roles:', error);
                        alert('Failed to fetch roles.');
                    });
            }

            // Edit role function to load data into the modal
            window.editRole = function (id) {
                fetch(`/roles/${id}`)
                    .then(response => response.json())
                    .then(role => {
                        if (role.error) {
                            alert(role.error);
                            return;
                        }

                        document.getElementById('roleId').value = role.id || '';
                        document.getElementById('role_name').value = role.role_name || '';
                        document.getElementById('is_active').value = role.is_active || '1';

                        openRoleModal();
                    })
                    .catch(error => {
                        console.error('Error fetching role:', error);
                        alert('An error occurred while fetching the role data.');
                    });
            };

            // Delete role function
            window.deleteRole = function (id) {
                if (confirm('Are you sure you want to delete this role?')) {
                    fetch(`/roles/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                            'Accept': 'application/json',
                        }
                    })
                        .then(() => fetchRoles())
                        .catch(error => {
                            console.error('Error deleting role:', error);
                            alert('An error occurred while deleting the role.');
                        });
                }
            };

            // Open the role modal
            function openRoleModal() {
                document.getElementById('roleModal').classList.remove('hidden');
            }

            // Close the modal
            function closeModal(modalId) {
                document.getElementById(modalId).classList.add('hidden');
            }
        });

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/petar/Desktop/SAE_FINAL_BACKEND/sites/event_management/resources/views/roles.blade.php ENDPATH**/ ?>