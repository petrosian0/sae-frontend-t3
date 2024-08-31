@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')

    <script src="https://cdn.tailwindcss.com"></script>
    <div class="container mx-auto mt-4 px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Manage Users</h1>
        <button id="addUserBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow mb-4">
            Add User
        </button>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full bg-white border-collapse border">
                <thead class="bg-gray-100 text-gray-800">
                    <tr>
                        <th class="py-3 px-4 border text-left">First Name</th>
                        <th class="py-3 px-4 border text-left">Last Name</th>
                        <th class="py-3 px-4 border text-left">Login Name</th>
                        <th class="py-3 px-4 border text-left">Role</th>
                        <th class="py-3 px-4 border text-left">Status</th>
                        <th class="py-3 px-4 border text-left">Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody" class="text-gray-700">
                    <!-- Users will be dynamically loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for Users -->
    <div id="userModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
                <div class="p-4 border-b">
                    <h2 id="userModalTitle" class="text-xl font-semibold text-gray-800">Add/Edit User</h2>
                </div>
                <form id="userForm" class="p-4">
                    <input type="hidden" id="userId" name="user_id">
                    <div class="mb-4">
                        <label for="first_name" class="block text-gray-700 font-medium mb-2">First Name</label>
                        <input type="text" id="first_name" name="first_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="last_name" class="block text-gray-700 font-medium mb-2">Last Name</label>
                        <input type="text" id="last_name" name="last_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="login_name" class="block text-gray-700 font-medium mb-2">Login Name</label>
                        <input type="text" id="login_name" name="login_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                        <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="role_id" class="block text-gray-700 font-medium mb-2">Role</label>
                        <select id="role_id" name="role_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="is_active" class="block text-gray-700 font-medium mb-2">Status</label>
                        <select id="is_active" name="is_active" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="flex justify-end border-t p-4">
                        <button id="closeUserModalBtn" type="button" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded shadow mr-2">
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
            fetchUsers();
            fetchRoles(); // Fetch roles to populate the dropdown in the form

            // Open modal for adding a new user
            document.getElementById('addUserBtn').addEventListener('click', function () {
                document.getElementById('userForm').reset(); // Reset the form
                document.getElementById('userId').value = ''; // Clear the hidden input for ID
                document.getElementById('password').required = true; // Require password for new users
                openUserModal();
            });

            // Close the user modal
            document.getElementById('closeUserModalBtn').addEventListener('click', function () {
                closeModal('userModal');
            });

            // Handle form submission
            document.getElementById('userForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const userId = document.getElementById('userId').value;
                const url = userId ? `/users/${userId}` : '/users';
                const method = userId ? 'PUT' : 'POST';

                // Collect form data as JSON object
                const formData = {
                    first_name: document.getElementById('first_name').value,
                    last_name: document.getElementById('last_name').value,
                    login_name: document.getElementById('login_name').value,
                    password: document.getElementById('password').value,
                    role_id: document.getElementById('role_id').value,
                    is_active: parseInt(document.getElementById('is_active').value, 10),
                };

                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.errors) {
                            alert('Validation failed: ' + JSON.stringify(data.errors));
                            return;
                        }
                        fetchUsers(); // Reload the users list
                        closeModal('userModal'); // Close the modal
                    })
                    .catch(error => {
                        alert('An error occurred: ' + (error.message || 'Unknown error'));
                    });
            });

            // Function to fetch users and display them in the table
            function fetchUsers() {
                fetch('/users/data')
                    .then(response => response.json())
                    .then(users => {
                        const tableBody = document.getElementById('usersTableBody');
                        tableBody.innerHTML = ''; // Clear current users
                        users.forEach(user => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="py-3 px-4 border">${user.first_name}</td>
                                <td class="py-3 px-4 border">${user.last_name}</td>
                                <td class="py-3 px-4 border">${user.login_name}</td>
                                <td class="py-3 px-4 border">${user.role ? user.role.role_name : 'N/A'}</td>
                                <td class="py-3 px-4 border">${user.is_active ? 'Active' : 'Inactive'}</td>
                                <td class="py-3 px-4 border">
                                    <button onclick="editUser(${user.id})" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-2 py-1 rounded">Edit</button>
                                    <button onclick="deleteUser(${user.id})" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-2 py-1 rounded">Delete</button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching users:', error);
                        alert('Failed to fetch users.');
                    });
            }

            // Function to fetch roles for the dropdown in the user form
            function fetchRoles() {
                fetch('/roles/data')
                    .then(response => response.json())
                    .then(roles => {
                        const roleSelect = document.getElementById('role_id');
                        roleSelect.innerHTML = ''; // Clear existing options
                        roles.forEach(role => {
                            const option = document.createElement('option');
                            option.value = role.id;
                            option.text = role.role_name;
                            roleSelect.appendChild(option);
                        });
                    });
            }

            // Function to load user data into the modal for editing
            window.editUser = function (id) {
                fetch(`/users/${id}`)
                    .then(response => response.json())
                    .then(user => {
                        // Populate modal fields with user data
                        document.getElementById('userId').value = user.id || '';
                        document.getElementById('first_name').value = user.first_name || '';
                        document.getElementById('last_name').value = user.last_name || '';
                        document.getElementById('login_name').value = user.login_name || '';
                        document.getElementById('password').required = false; // Password is optional for edits
                        document.getElementById('role_id').value = user.role_id || '';
                        document.getElementById('is_active').value = user.is_active || '1';

                        openUserModal();
                    })
                    .catch(error => {
                        console.error('Error fetching user:', error);
                        alert('An error occurred while fetching the user data.');
                    });
            };

            // Function to delete a user
            window.deleteUser = function (id) {
                if (confirm('Are you sure you want to delete this user?')) {
                    fetch(`/users/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    })
                        .then(() => fetchUsers()) // Refresh users after deletion
                        .catch(error => {
                            console.error('Error deleting user:', error);
                            alert('An error occurred while deleting the user.');
                        });
                }
            };

            // Function to open the user modal and reset the form
            function openUserModal() {
                document.getElementById('userModal').classList.remove('hidden');
            }

            // Function to close the modal
            function closeModal(modalId) {
                document.getElementById(modalId).classList.add('hidden');
            }
        });
    </script>
@endsection
