<nav x-data="{ open: false }" @keydown.window.escape="open = false" class="bg-indigo-700">

    {{-- desktop navigation --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    @include('shared.icons.logo', ['size' => 'h-8 w-8'])
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline">

                        <a href="{{ route('app.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('app.dashboard') ? 'text-white bg-indigo-800' : 'text-indigo-200 hover:text-white hover:bg-indigo-600' }} focus:outline-none focus:text-white focus:bg-indigo-600 transition ease-in-out duration-150">Dashboard</a>

                        <a href="{{ route('app.settings') }}" class="ml-4 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('app.settings') ? 'text-white bg-indigo-800' : 'text-indigo-200 hover:text-white hover:bg-indigo-600' }} focus:outline-none focus:text-white focus:bg-indigo-600 transition ease-in-out duration-150">Settings</a>

                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">

                    @can('view-horizon', \App\Base\Models\User::class)
                        <a href="{{ route('horizon.index') }}" class="p-1 border-2 border-transparent text-indigo-300 rounded-full hover:text-white focus:outline-none focus:text-white focus:bg-indigo-600 transition ease-in-out duration-150">
                            @include('shared.icons.horizon')
                        </a>
                    @endcan

                    @can('view-telescope', \App\Base\Models\User::class)
                        <a href="{{ route('telescope') }}" class="p-1 border-2 border-transparent text-indigo-300 rounded-full hover:text-white focus:outline-none focus:text-white focus:bg-indigo-600 transition ease-in-out duration-150">
                            @include('shared.icons.telescope')
                        </a>
                    @endcan

                    <div @click.away="open = false" class="ml-3 relative" x-data="{ open: false }">
                        <div>
                            <button @click="open = !open" class="max-w-xs flex items-center text-sm rounded-full text-white focus:outline-none focus:shadow-solid">
                                <img class="h-8 w-8 rounded-full" src="{{ Auth::user()->gravatar_url }}?s=64" alt="{{ Auth::user()->name }}" />
                            </button>
                        </div>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg">
                            <div class="rounded-md bg-white shadow-xs">
                                <div class="px-4 py-3">
                                    <p class="text-sm leading-5">
                                        Signed in as
                                    </p>
                                    <p class="text-sm leading-5 font-medium text-gray-900">
                                        {{ Auth::user()->name }}
                                    </p>
                                </div>
                                <div class="border-t border-gray-100"></div>
                                <div class="py-1">
                                    <a href="#" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">Profile</a>
                                    <a href="#" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">Help</a>
                                </div>
                                <div class="border-t border-gray-100"></div>
                                <div class="py-1">
                                    <form action="{{ route('logout') }}" method="POST">
                                        {{ csrf_field() }}
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">
                                            Sign out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="-mr-2 flex md:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-indigo-300 hover:text-white hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600 focus:text-white">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- mobile navigation --}}
    <div :class="{'block': open, 'hidden': !open}" class="hidden md:hidden">
        <div class="px-2 pt-2 pb-3 sm:px-3">

            <a href="{{ route('app.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('app.dashboard') ? 'text-white bg-indigo-800' : 'text-indigo-200 hover:text-white hover:bg-indigo-600' }} focus:outline-none focus:text-white focus:bg-gray-700">Dashboard</a>

            <a href="{{ route('app.settings') }}" class="mt-1 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('app.settings') ? 'text-white bg-indigo-800' : 'text-indigo-200 hover:text-white hover:bg-indigo-600' }} focus:outline-none focus:text-white focus:bg-indigo-600">Settings</a>

            @can('view-horizon', \App\Base\Models\User::class)
                <a href="{{ route('horizon.index') }}" class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-indigo-200 hover:text-white hover:bg-indigo-600 focus:outline-none focus:text-white focus:bg-indigo-600">{{ __('Horizon') }}</a>
            @endcan

            @can('view-telescope', \App\Base\Models\User::class)
                <a href="{{ route('telescope') }}" class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-indigo-200 hover:text-white hover:bg-indigo-600 focus:outline-none focus:text-white focus:bg-indigo-600">{{ __('Telescope') }}</a>
            @endcan

        </div>
        <div class="pt-4 pb-3 border-t border-gray-700">
            <div class="flex items-center px-5">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full" src="{{ Auth::user()->gravatar_url }}?s=64" alt="{{ Auth::user()->name }}" />
                </div>
                <div class="ml-3">
                    <div class="text-sm font-medium leading-none text-indigo-300">Signed in as</div>
                    <div class="mt-1 text-base font-medium leading-none text-white">{{ Auth::user()->name }}</div>
                </div>
            </div>
            <div class="mt-3 px-2">
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-indigo-300 hover:text-white hover:bg-indigo-600 focus:outline-none focus:text-white focus:bg-indigo-600">Profile</a>
                <a href="#" class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-indigo-300 hover:text-white hover:bg-indigo-600 focus:outline-none focus:text-white focus:bg-indigo-600">Help</a>
                <form action="{{ route('logout') }}" method="POST">
                    {{ csrf_field() }}
                    <button type="submit" class="mt-1 block w-full px-3 py-2 rounded-md text-base text-left font-medium text-indigo-300 hover:text-white hover:bg-indigo-600 focus:outline-none focus:text-white focus:bg-indigo-600">
                        Sign out
                    </button>
                </form>
            </div>
        </div>
    </div>

</nav>
