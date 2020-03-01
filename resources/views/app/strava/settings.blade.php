@extends('layouts.app')

@section('title', 'Strava Account Settings')

@section('meta')
    <div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap">
        <div class="mt-2 flex items-center text-sm leading-5 sm:mr-6">
            @include('partials.strava.status', ['athlete' => $athlete])
        </div>
        <div class="mt-2 flex items-center text-sm leading-5 text-gray-500 sm:mr-6">
            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
            </svg>
            {{ $athlete->first_name }} {{ $athlete->last_name }}
        </div>
    </div>
@endsection

@section('content')
    <div class="bg-white shadow overflow-hidden  sm:rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Danger Zone
            </h3>
            <p class="mt-1 max-w-2xl text-sm leading-5 text-gray-500">
                Options to pause or disconnect this account.
            </p>
        </div>

        <div class="px-4 py-5 sm:px-6">
            <div class="">

                <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center">
                    <div class="mt-2 sm:mt-0">
                        <form action="{{ route('app.strava.athlete.update', ['athlete' => $athlete]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="paused_at" value="@if(!$athlete->isPaused()){{ now() }}@endif">
                            <button type="submit" class="py-2 px-3 border border-indigo-300 rounded-md text-sm leading-4 font-medium text-indigo-700 hover:text-indigo-500 focus:outline-none focus:border-indigo-300 focus:shadow-outline-indigo active:bg-indigo-50 active:text-indigo-800 transition duration-150 ease-in-out">
                                @if($athlete->isPaused())
                                    Continue Account
                                @else
                                    Pause Account
                                @endif
                            </button>
                        </form>
                    </div>
                    <p class="mt-2 text-sm leading-5 text-gray-500 sm:col-span-2">
                        Pausing the account will stop Skycle from decorating its activities while still keeping it connected.
                    </p>
                </div>

                <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:border-t sm:border-gray-200 sm:pt-5">
                    <div class="mt-2 sm:mt-0" x-data="{ deleteAccountModalOpen: false }">
                        <form action="{{ route('app.strava.athlete.destroy', ['athlete' => $athlete]) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button @click.prevent="deleteAccountModalOpen = true" type="submit" class="py-2 px-3 border border-red-300 rounded-md text-sm leading-4 font-medium text-red-700 hover:text-red-500 focus:outline-none focus:shadow-outline-red active:bg-red-50 active:text-red-800 transition duration-150 ease-in-out">
                                Delete Account
                            </button>
                        </form>
                        @include('partials.strava.delete-account-modal')
                    </div>
                    <p class="mt-2 text-sm leading-5 text-gray-500 sm:col-span-2">
                        Deleting the account will remove Skycles access and permanently remove any records and settings. This cannot be undone.
                    </p>
                </div>

            </div>
        </div>
    </div>
@endsection
