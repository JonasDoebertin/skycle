@extends('layouts.auth')

@section('content')

    <div class="text-center">
        @include('shared.icons.logo', ['size' => 'h-20 w-20'])
        <h2 class="mt-6 text-center text-sm leading-5 font-bold text-gray-900">
            Sign in to Skycle
        </h2>
    </div>

    <form class="mt-8" method="POST" action="{{ route('login') }}">
        @csrf
        <input type="hidden" name="remember" value="true" />

        <div class="rounded-md shadow-sm">
            <div>
                <input aria-label="Email address" name="email" type="email" value="{{ old('email') }}" required class="appearance-none rounded-none relative block w-full px-3 py-2 border {{ $errors->has('email') ? ' border-red-300 placeholder-red-500' : 'border-gray-300 placeholder-gray-500' }} text-gray-900 rounded-t-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="Email address" />
            </div>
            <div class="-mt-px relative">
                <input aria-label="Password" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border {{ $errors->has('email') ? ' border-red-300 placeholder-red-500' : 'border-gray-300 placeholder-gray-500' }} text-gray-900 rounded-b-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="Password" />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                    <a href="{{ route('password.request') }}" class="text-indigo-600 hover:text-indigo-500 z-20">Forgot?</a>
                </div>
            </div>
        </div>

        @if ($errors->has('email'))
            <p class="mt-2 text-sm leading-5 text-red-600">
                These credentials do not match any of our users.
            </p>
        @endif

        <div class="mt-6">
            <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
          <span class="absolute left-0 inset-y pl-3">
            <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400 transition ease-in-out duration-150" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
            </svg>
          </span>
                Sign in
            </button>
        </div>
    </form>

    <div class="mt-6">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm leading-5">
                <span class="px-2 bg-gray-50 text-gray-500">Don't have an account yet?</span>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('register') }}" class="block w-full text-center py-2 px-3 border border-indigo-300 rounded-md text-indigo-900 font-medium hover:border-indigo-400 focus:outline-none focus:shadow-outline-indigo focus:border-indigo-400 sm:text-sm sm:leading-5">
                Register
            </a>
        </div>
    </div>

@endsection
