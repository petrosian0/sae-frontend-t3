<nav class="bg-gray-800">
  <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
    <div class="relative flex h-16 items-center justify-between">
      <!-- Logo or Brand -->
      <div class="flex flex-1 items-center justify-start">
        <div class="flex-shrink-0">
          <!-- Add a logo or brand name here if needed -->
          <a href="{{ route('home') }}" class="text-white text-2xl font-bold">Event Management</a>
        </div>
      </div>
      
      @auth
      <!-- Centered Navigation Links -->
      <div class="flex-1 flex justify-center mx-4">
        <div class="flex space-x-4">
          <a href="{{ route('home') }}" class="rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('home') ? 'text-white bg-gray-900' : 'text-gray-300 hover:bg-gray-700' }}" aria-current="page">
            Home
          </a>
          <a href="{{ route('calendar') }}" class="rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('calendar') ? 'text-white bg-gray-900' : 'text-gray-300 hover:bg-gray-700' }}">
            Calendar
          </a>
          <a href="{{ route('settings') }}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
            Settings
          </a>
        </div>
      </div>
      @endauth

      <!-- Authentication Buttons and User Info -->
      <div class="flex items-center space-x-4">
        @guest
          <a href="{{ route('login') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out">
            Login
          </a>
          <a href="{{ route('register') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out">
            Sign In
          </a>
        @endguest

        @auth
          <span class="text-white">{{ Auth::user()->login_name }}</span>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-150 ease-in-out">
              Logout
            </button>
          </form>
        @endauth
      </div>
    </div>
  </div>
</nav>
