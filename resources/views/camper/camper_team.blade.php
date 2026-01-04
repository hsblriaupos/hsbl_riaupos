@extends('admin.layouts.app')
@section('title', 'Camper Data - Administrator')
@section('content')

@push('scripts')
@include('partials.sweetalert')
@endpush

@push('styles')
<link href="{{ asset('css/data.css') }}" rel="stylesheet" />
@endpush

<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Daftar Camper</h1>

    <div class="overflow-auto rounded-xl shadow-sm border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-800 font-medium">
            <thead class="bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="py-3 px-3 text-center border border-gray-300">No</th>
                    <th class="py-3 px-4 text-left border border-gray-300">Name</th>
                    <th class="py-3 px-4 text-left border border-gray-300">Team Category</th>
                    <th class="py-3 px-4 text-center border border-gray-300">Jersey</th>
                    <th class="py-3 px-4 text-center border border-gray-300">Role</th>
                    <th class="py-3 px-4 text-left border border-gray-300">School</th>
                    <th class="py-3 px-4 text-center border border-gray-300">Season</th>
                    <th class="py-3 px-4 text-center border border-gray-300">Status</th>
                    <th class="py-3 px-4 text-center border border-gray-300">Action</th>
                </tr>
            </thead>

            <tbody class="bg-white">
                @forelse($campers as $index => $camper)
                <tr class="hover:bg-blue-50 transition duration-200 ease-in-out">
                    <td class="text-center py-3 border border-gray-300">{{ $index + 1 }}</td>
                    <td class="py-3 px-4 font-semibold border border-gray-300">{{ $camper->player->name ?? '-' }}</td>
                    <td class="text-center border border-gray-300">
                        {{ $camper->player->team->team_category ?? '-' }}
                    </td>

                    <td class="text-center border border-gray-300">{{ $camper->player->jersey_number ?? '-' }}</td>
                    <td class="text-center border border-gray-300">
                        <span class="inline-block px-2 py-1 text-xs rounded-full font-semibold
                            {{ $camper->player->team_role == 'Leader' ? 'bg-red-100 text-red-700 border border-red-300' : 'bg-green-100 text-green-700 border border-green-300' }}">
                            {{ $camper->player->team_role ?? '-' }}
                        </span>
                    </td>
                    <td class="border px-4 py-2 border-gray-300">{{ $camper->player->schoolData->school_name ?? '-' }}</td>
                    <td class="text-center border border-gray-300">{{ $camper->season->season_name ?? '-' }}</td>
                    <td class="text-center border border-gray-300">
                        <span class="inline-block px-2 py-1 text-xs rounded-full font-semibold
                        {{ 
                    $camper->camper_status == 'Selected' ? 'bg-blue-100 text-blue-700 border border-blue-300' :
                    ($camper->camper_status == 'Reserve' ? 'bg-yellow-100 text-yellow-800 border border-yellow-300' :
                    'bg-gray-100 text-gray-800 border border-gray-300')
                        }}">
                            {{ $camper->camper_status }}
                        </span>
                    </td>
                    <td class="text-center border border-gray-300">
                        <a href="{{ route('admin.camper.detail', $camper->player->id) }}"
                            class="inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1.5 rounded transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12H9m12 0A9 9 0 113 12a9 9 0 0118 0z" />
                            </svg>
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-6 text-gray-400 italic border border-gray-300">Belum ada camper terdaftar.</td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>
@endsection