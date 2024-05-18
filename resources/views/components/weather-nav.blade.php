    @auth
        @if(isset($user))
            <input id="city" name="city" type="hidden" value="{{ $user->city }}">
        @else 
            <input id="city" name="city" type="hidden" value="Bratislava">

        @endif
    @endauth
    @guest
        <input id="city" name="city" type="hidden" value="Bratislava">
    @endguest
    <div id="rain-popup" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
          <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Rainy Days Ahead</h3>
            <div class="mt-2 px-7 py-3">
              <p class="text-sm text-gray-500">It may be raining in the next 3 days. Don't forget your umbrella!</p>
            </div>
            <div class="items-center px-4 py-3">
              <button id="close-btn" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Close
              </button>
            </div>
          </div>
        </div>
      </div>
    <div id="loading-indicator" class="hidden">Loading...</div>

    <div class="min-h-screen flex flex-col">
        <nav class="bg-white shadow-md z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo and Primary Navigation Links -->
                    <div class="flex items-center space-x-4">
                        <!-- Logo area -->
                        <div class="flex-shrink-0 flex items-center">
                            <img class="block lg:hidden h-8 w-auto" src="/img/weather-icon.svg" alt="Logo">
                            <img class="hidden lg:block h-10 w-auto" src="/img/weather-icon.svg" alt="Logo">
                        </div>
                
                        <!-- Primary Nav Links -->
                        <div class="flex space-x-1">
                        <a href="/my-farm" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-blue-500 hover:bg-gray-100">
                            My Farm
                        </a>
                        <a href="/planned-actions" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-blue-500 hover:bg-gray-100">
                            Planned Actions
                        </a>
                        <a href="/community" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-blue-500 hover:bg-gray-100">
                            Community
                        </a>
                        </div>
                    </div>
            
                    <!-- Forecast Toggles -->
                    <div class="flex items-center space-x-4">
                        <button id="daily-btn" class="text-sm px-4 py-2 leading-5  transition-colors duration-150 border border-blue-700 rounded-lg focus:shadow-outline hover:bg-blue-700 hover:text-white">
                        Daily Forecast
                        </button>
                        <button id="weekly-btn" class="text-sm px-4 py-2 leading-5  transition-colors duration-150 border border-blue-700 rounded-lg focus:shadow-outline hover:bg-blue-700 hover:text-white">
                        Weekly Forecast
                        </button>
                        <form action="{{ route('checkIfExtremeWeather') }}" method="get">
                            <button type="submit" id="extreme-weather-btn" class="text-white bg-blue-700 px-4 py-2 leading-5 transition-colors duration-150 border border-blue-700 rounded-lg focus:outline-none hover:bg-blue-800 hover:text-white">
                                Extreme weather check
                            </button>
                        </form>
                    </div>
            
                    @guest
                        <div class="flex items-center">
                            <a href="/login" class="text-sm px-3 py-2 rounded-md text-gray-700 hover:text-blue-500 hover:bg-gray-100">
                            Log In
                            </a>
                            <a href="/register" class="text-sm px-3 py-2 rounded-md text-gray-700 hover:text-blue-500 hover:bg-gray-100">
                                Register
                            </a>
                        </div>        
                    @endguest
                    @auth
                        <div class="flex items-center">
                            <a href="/profile" class="text-sm px-3 py-2 rounded-md text-gray-700 hover:text-blue-500 hover:bg-gray-100">
                                Profile
                            </a>
                            <form action="/logout" method="post">
                                @csrf
                                <button type="submit" href="/logout" class="text-sm px-3 py-2 rounded-md text-gray-700 hover:text-blue-500 hover:bg-gray-100">
                                    Log out
                                </a>
                            </form>
                        </div>
                    @endauth

                </div>
            </div>
        </nav>