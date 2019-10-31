@extends('layouts.app')

@section('content')
    <div class="flex items-center">
        <div class="md:w-1/2 md:mx-auto">

            @if (flash()->message)
                <div class="text-sm border border-t-8 rounded px-3 py-4 mb-4 {{ flash()->class }}" role="alert">
                    {{ flash()->message }}
                </div>
            @endif

            <div class="flex flex-col break-words bg-white border border-2 rounded shadow-md mb-8">

                <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
                    Dashboard
                </div>

                <div class="w-full p-6">
                    <p class="text-gray-700">
                        Welcome back!
                    </p>
                </div>
            </div>

            <div class="flex flex-col break-words bg-white border border-2 rounded shadow-md">

                <div class="flex justify-between font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
                    <h2>Strava Accounts</h2>
                    <a href="{{ route('strava.oauth.authorize') }}">+ Add new</a>
                </div>

                <ul class="w-full p-6">
                    @foreach ($stravaAccounts as $stravaAccount)
                        <li class="text-gray-700">
                            <img src="{{ $stravaAccount->profile_picture }}" alt="{{ $stravaAccount->firstName }} {{ $stravaAccount->lastName }} on Strava">
                            {{ $stravaAccount->first_name }} {{ $stravaAccount->last_name }}
                            Remove
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
