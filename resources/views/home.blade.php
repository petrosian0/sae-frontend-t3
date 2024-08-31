@extends('layouts.app')

@section('title', 'Home')

@section('content')


<script src="https://cdn.tailwindcss.com"></script>

<div class="flex flex-col items-center mt-10">
    <div class="w-full max-w-4xl bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Event Report</h2>

        @if($events->isEmpty())
            <p class="text-center text-gray-500">No events found.</p>
        @else
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 border">ID</th>
                        <th class="px-4 py-2 border">Title</th>
                        <th class="px-4 py-2 border">Start Date</th>
                        <th class="px-4 py-2 border">End Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                        <tr>
                            <td class="px-4 py-2 border">{{ $event->id }}</td>
                            <td class="px-4 py-2 border">{{ $event->title }}</td>
                            <td class="px-4 py-2 border">{{ $event->start_date }}</td>
                            <td class="px-4 py-2 border">{{ $event->end_date }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection

