@extends('layouts.auth')

@section('content')
    <div class="px-4 pb-4 sm:flex sm:items-center sm:justify-center">
        <div class="bg-white rounded-lg px-4 pt-5 pb-4 overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full sm:p-6">
            <div>
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    @include('shared.icons.shield', ['size' => 'h-6 w-6', 'color' => 'text-red-600'])
                </div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Verify your email address
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm leading-5 text-gray-500">
                            Before proceeding, please check your email for a verification link. If you did not receive the email, you can resend another.
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                @if (session('resent'))
                    <div class="text-sm border border-t-8 rounded text-green-700 border-green-600 bg-green-100 px-3 py-4" role="alert">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                @else
                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <form action="{{ route('verification.resend') }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 shadow-sm hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition ease-in-out duration-150">
                                Resend
                            </button>
                        </form>
                    </span>
                    <span class="flex-grow"></span>
                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                        <a href="{{ url()->previous() }}" class="inline-flex justify-center w-full rounded-md border border-indigo-300 px-4 py-2 text-base leading-6 font-medium text-indigo-700 hover:text-indigo-500 focus:outline-none focus:border-indigo-300 focus:shadow-outline-indigo active:bg-indigo-50 active:text-indigo-800 transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Back
                        </a>
                    </span>
                @endif
            </div>
        </div>
    </div>
@endsection
