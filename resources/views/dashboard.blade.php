@extends('layouts.app')

@section('breadcrumb', '')
@section('title', 'Dashboard')

@section('content')

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
            <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-no-wrap">
                <div class="ml-4 mt-2">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Connected Strava Accounts
                    </h3>
                </div>
                <div class="ml-4 mt-2 flex-shrink-0">
                    <span class="inline-flex rounded-md shadow-sm">
                        <a href="{{ route('app.strava.oauth.authorize') }}" class="relative inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 shadow-sm hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition ease-in-out duration-150">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Add Another</span>
                        </a>
                    </span>
                </div>
            </div>
        </div>
        <ul>
            @foreach ($stravaAccounts as $stravaAccount)
                <li @if (!$loop->first)class="border-t border-gray-200"@endif>
                    <a href="{{ route('app.strava.athlete.show', ['athlete' => $stravaAccount]) }}" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
                        <div class="flex items-center px-4 py-4 sm:px-6">
                            <div class="min-w-0 flex-1 flex items-center">
                                <div class="flex-shrink-0 mr-4">
                                    <img class="h-12 w-12 rounded-full" src="{{ $stravaAccount->profile_picture }}" alt="{{ $stravaAccount->firstName }} {{ $stravaAccount->lastName }} on Strava">
                                </div>
                                <div class="flex-shrink-0 mr-4">
                                    <div class="text-sm leading-5 font-medium text-indigo-600 truncate">
                                        {{ $stravaAccount->first_name }} {{ $stravaAccount->last_name }}
                                    </div>
                                    <div class="mt-2 flex items-center text-sm leading-5 text-gray-500">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884zM18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="truncate">jonas@doebertin.de</span>
                                    </div>
                                </div>
                                <div class="flex-grow"></div>
                                <div class="flex-shrink-0 mr-4 text-right hidden md:block">
                                    @include('partials.strava.status', ['athlete' => $stravaAccount])
                                    <div class="mt-2 text-sm leading-5 text-gray-500">
                                        <span>
                                            Added on
                                            <time datetime="{{ $stravaAccount->created_at->toDateString() }}">{{ $stravaAccount->created_at->toFormattedDateString() }}</time>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

@endsection
