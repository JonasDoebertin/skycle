@extends('layouts.app')

@section('breadcrumb', '')
@section('title', 'Cleaners')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-2 bg-white shadow sm:rounded-md">
            <ul>
                @php $wasOdd = false @endphp
                @foreach ($cleaners as $cleaner)
                    @php $wasOdd = $loop->odd @endphp
                    <li @if (!$loop->first)class="border-t border-gray-200"@endif>
                        <div @if ($loop->even)class="bg-gray-50"@endif>
                            <form class="px-4 py-4 flex items-center sm:px-6" action="{{ route('app.cleaners.destroy', ['cleaner' => $cleaner]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                                    <div>
                                        <div class="text-sm leading-5 text-gray-900 truncate">
                                            {{ $cleaner->text }}
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="ml-5 flex-shrink-0 text-gray-400 hover:text-red-400 focus:outline-none focus:text-red-400 active:text-red-400 transition ease-in-out duration-150 focus:shadow-outline-red">
                                    @include('shared.icons.trash', ['size' => 'h5 w-5', 'color' => ''])
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
                <li class="border-t border-gray-200">
                    <form class="h-13 relative" action="{{ route('app.cleaners.store') }}" method="POST">
                        @csrf
                        <input class="absolute inset-0 min-w-0 px-4 py-4 sm:px-6 w-full sm:rounded-b-md {{ $wasOdd ? 'bg-gray-50' : ''}} text-sm text-gray-900 focus:outline-none focus:shadow-outline-green" type="text" name="text" required placeholder="Add another one…">
                        <button type="submit" class="absolute inset-0 left-auto mr-4 my-4 md:mr-6 text-gray-400 hover:text-green-400 focus:outline-none focus:text-green-400 active:text-green-400 transition ease-in-out duration-150 focus:shadow-outline-green">
                            @include('shared.icons.plus', ['size' => 'h5 w-5', 'color' => ''])
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="bg-white px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    What's the fuzz?
                </h3>
                <p class="mt-1 text-sm leading-5 text-gray-500">
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
                </p>
            </div>
        </div>
    </div>
@endsection
