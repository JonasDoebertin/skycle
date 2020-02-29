@extends('layouts.auth')

@section('content')

    <div class="text-center">
        @include('shared.icons.logo', ['size' => 'h-20 w-20'])
        <h2 class="mt-6 text-center text-sm leading-5 font-bold text-gray-900">
            Reset your password
        </h2>
        <p class="mt-5 text-sm leading-5 text-center text-gray-600">
            Enter your email and we'll send you a link to reset your password.
        </p>
    </div>

    @if (session('status'))
        <div class="text-sm border border-t-8 rounded text-green-700 border-green-600 bg-green-100 px-3 py-4 mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form class="mt-8" method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="rounded-md shadow-sm">
            <div>
                <input aria-label="Email address" name="email" type="email" value="{{ old('email') }}" required class="appearance-none relative block w-full px-3 py-2 border {{ $errors->has('email') ? ' border-red-300 placeholder-red-500' : 'border-gray-300 placeholder-gray-500' }} text-gray-900 rounded-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="Email address" />
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
                Reset
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


{{--    <div class="container mx-auto">--}}
{{--        <div class="flex flex-wrap justify-center">--}}
{{--            <div class="w-full max-w-sm">--}}

{{--                @if (session('status'))--}}
{{--                    <div class="text-sm border border-t-8 rounded text-green-700 border-green-600 bg-green-100 px-3 py-4 mb-4" role="alert">--}}
{{--                        {{ session('status') }}--}}
{{--                    </div>--}}
{{--                @endif--}}

{{--                <div class="flex flex-col break-words bg-white border border-2 rounded shadow-md">--}}

{{--                    <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">--}}
{{--                        {{ __('Reset Password') }}--}}
{{--                    </div>--}}

{{--                    <form class="w-full p-6" method="POST" action="{{ route('password.email') }}">--}}
{{--                        @csrf--}}

{{--                        <div class="flex flex-wrap mb-6">--}}
{{--                            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">--}}
{{--                                {{ __('E-Mail Address') }}:--}}
{{--                            </label>--}}

{{--                            <input id="email" type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline{{ $errors->has('email') ? ' border-red' : '' }}" name="email" value="{{ old('email') }}" required>--}}

{{--                            @if ($errors->has('email'))--}}
{{--                                <p class="text-red-500 text-xs italic mt-4">--}}
{{--                                    {{ $errors->first('email') }}--}}
{{--                                </p>--}}
{{--                            @endif--}}
{{--                        </div>--}}

{{--                        <div class="flex flex-wrap">--}}
{{--                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-gray-100 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">--}}
{{--                                {{ __('Send Password Reset Link') }}--}}
{{--                            </button>--}}

{{--                            <p class="w-full text-xs text-center text-grey-dark mt-8 -mb-4">--}}
{{--                                <a class="text-blue-500 hover:text-blue-700 no-underline" href="{{ route('login') }}">--}}
{{--                                    Back to login--}}
{{--                                </a>--}}
{{--                            </p>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection
