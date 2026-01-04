@extends('admin.layouts.app')
@section('title', 'Master Data - Administrator')
@section('content')

@php $activeTab = 'result'; @endphp
@include('partials.tabs_pub', compact('activeTab'))

@push('styles')
<link href="{{ asset('css/data.css') }}" rel="stylesheet" />
<style>
    /* custom styles if needed */
</style>
@endpush

@push('scripts')
@include('partials.sweetalert')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const team1 = document.getElementById('editTeam1');
        const team2 = document.getElementById('editTeam2');

        function validateTeams() {
            if (team1.value && team2.value && team1.value === team2.value) {
                alert('Team 1 dan Team 2 tidak boleh sama!');
                team2.value = '';
            }
        }

        team1.addEventListener('change', validateTeams);
        team2.addEventListener('change', validateTeams);
    });

    function openEditMatchResultModal(button) {
        const id = button.getAttribute('data-id');
        const date = button.getAttribute('data-date');
        const team1_id = button.getAttribute('data-team1_id');
        const team2_id = button.getAttribute('data-team2_id');
        const score_1 = button.getAttribute('data-score_1'); // perhatikan ini
        const score_2 = button.getAttribute('data-score_2'); // perhatikan ini
        const competition = button.getAttribute('data-competition');
        const competition_type = button.getAttribute('data-competition_type');
        const phase = button.getAttribute('data-phase');

        document.getElementById('editMatchResultId').value = id;
        document.getElementById('editMatchDate').value = date;
        document.getElementById('editTeam1').value = team1_id;
        document.getElementById('editTeam2').value = team2_id;
        document.getElementById('editScore1').value = score_1;
        document.getElementById('editScore2').value = score_2;
        document.getElementById('editCompetition').value = competition;
        document.getElementById('editCompetitionType').value = competition_type;
        document.getElementById('editPhase').value = phase;

        const form = document.querySelector('#editMatchResultModal form');
        form.action = `/admin/result/${id}`;

        const editModal = new bootstrap.Modal(document.getElementById('editMatchResultModal'));
        editModal.show();
    }
</script>

@endpush

{{-- === Form Tambah Match Result === --}}
<div class="py-4">
    <h2 class="text-2xl font-bold mb-6">Match Result</h2>
    <div class="bg-white p-6 rounded shadow mb-2">
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-semibold text-md">Add Result</h3>
        </div>

        <form action="{{ route('admin.result.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="date" class="block font-medium mb-1">Tanggal Pertandingan</label>
                    <input type="date" name="date" id="date" class="w-full border rounded p-2" required>
                </div>

                <div>
                    <label for="competition" class="block font-medium mb-1">Competition</label>
                    <select name="competition" id="competition" class="w-full border rounded p-2" required>
                        <option value="">Pilih Competition</option>
                        @foreach ($competitions as $competition)
                        <option value="{{ $competition }}">{{ $competition }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="team1_id" class="block font-medium mb-1">Team 1 (Sekolah)</label>
                    <select name="team1_id" id="team1_id" class="w-full border rounded p-2" required>
                        <option value="">Pilih Sekolah</option>
                        @foreach ($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="team2_id" class="block font-medium mb-1">Team 2 (Sekolah)</label>
                    <select name="team2_id" id="team2_id" class="w-full border rounded p-2" required>
                        <option value="">Pilih Sekolah</option>
                        @foreach ($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="score_team1" class="block font-medium mb-1">Score Team 1</label>
                    <input type="number" name="score_team1" id="score_team1" class="w-full border rounded p-2" required min="0">
                </div>

                <div>
                    <label for="score_team2" class="block font-medium mb-1">Score Team 2</label>
                    <input type="number" name="score_team2" id="score_team2" class="w-full border rounded p-2" required min="0">
                </div>

                <div>
                    <label for="competition_type" class="block font-medium mb-1">Competition Type</label>
                    <select name="competition_type" id="competition_type" class="w-full border rounded p-2" required>
                        <option value="">-- Pilih Competition Type --</option>
                        @foreach ($competitionTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="phase" class="block font-medium mb-1">Phase</label>
                    <select name="phase" id="phase" class="w-full border rounded p-2" required>
                        <option value="">Pilih Phase</option>
                        @foreach ($phases as $phase)
                        <option value="{{ $phase }}">{{ $phase }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2">
                    <label for="scoresheet" class="block font-medium mb-1">Upload Scoresheet</label>
                    <input type="file" name="scoresheet" id="scoresheet" accept=".pdf,.jpg,.jpeg,.png,.xls,.xlsx" class="form-control">
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">Simpan</button>
            </div>
        </form>
    </div>


    {{-- === List Match Result === --}}
    <div class="bg-white p-6 rounded shadow">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
            <h3 class="text-lg font-semibold md:mb-0">List Match Result</h3>
        </div>

        <div class="overflow-x-auto">
        <table class="min-w-full text-xs text-left border">
        <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border text-center">No</th>
                        <th class="px-2 py-2 border text-centerr">Match Date</th>
                        <th class="px-2 py-2 border text-center">Team A</th>
                        <th class="px-3 py-2 border text-center">Score</th>
                        <th class="px-2 py-2 border text-center">Team B</th>
                        <th class="px-4 py-2 border text-center">Phase</th>
                        <th class="px-3 py-2 border text-center">Competition</th>
                        <th class="px-3 py-2 border text-center">Competition Type</th>
                        <th class="px-2 py-2 border text-center">Scoresheet</th>
                        <th class="px-3 py-2 border text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($matchResults as $match)
                    <tr class="border-t">
                        <td class="px-3 py-2 border text-center">{{ $loop->iteration + ($matchResults->currentPage() - 1) * $matchResults->perPage() }}</td>
                        <td class="px-2 py-2 border">{{ \Carbon\Carbon::parse($match->match_date)->format('d M Y') }}</td>
                        <td class="px-2 py-2 border">{{ $match->team1->school_name ?? '-' }}</td>
                        <td class="px-2 py-2 border text-center ">{{ $match->score_1 ?? '-' }} - {{ $match->score_2 ?? '-' }}</td>
                        <td class="px-2 py-2 border">{{ $match->team2->school_name ?? '-' }}</td>
                        <td class="px-2 py-2 border">{{ $match->phaseData->phase ?? $match->phase ?? '-' }}</td>
                        <td class="px-3 py-2 border">{{ $match->competition ?? '-' }}</td>
                        <td class="px-3 py-2 border">{{ $match->competitionTypeData->competition_type ?? $match->competition_type ?? '-' }}</td>
                        <td class="px-2 py-2 border text-center">
                            @if(!empty($match->scoresheet))
                            <a href="{{ Storage::url($match->scoresheet) }}" target="_blank" class="text-blue-600 underline">View</a>
                            @else
                            <span class="text-gray-400 italic">No file</span>
                            @endif
                        </td>
                        <td class="p-2 border text-center space-x-2 whitespace-nowrap">
                            <button
                                type="button"
                                class="text-blue-500 text-xs"
                                data-id="{{ $match->id }}"
                                data-date="{{ $match->match_date }}"
                                data-team1_id="{{ $match->team1_id }}"
                                data-team2_id="{{ $match->team2_id }}"
                                data-score_1="{{ $match->score_1 }}"
                                data-score_2="{{ $match->score_2 }}"
                                data-competition="{{ $match->competition }}"
                                data-competition_type="{{ $match->competition_type }}"
                                data-phase="{{ $match->phase }}"
                                onclick="openEditMatchResultModal(this)">
                                ✏️ Edit
                            </button>
                            <form method="POST" action="{{ route('admin.data.delete', $match->id) }}" class="inline"
                                onsubmit="return confirm('Yakin ingin hapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 text-xs">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

            <div class="mt-4">
                {{ $matchResults->links() }}
            </div>
        </div>

        <!-- Modal Edit Match Result -->
        <div class="modal fade" id="editMatchResultModal" tabindex="-1" aria-labelledby="editMatchResultModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" enctype="multipart/form-data" id="editMatchResultForm">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="id" id="editMatchResultId" />

                        <div class="modal-header">
                            <h5 class="modal-title" id="editMatchResultModalLabel">Edit Match Result</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="editMatchDate" class="form-label">Tanggal Pertandingan</label>
                                <input type="date" name="match_date" id="editMatchDate" class="form-control" required>
                            </div>

                            <div>
                                <label for="editCompetition" class="form-label">Competition</label>
                                <select name="competition" id="editCompetition" class="form-select" required>
                                    <option value="">Pilih Competition</option>
                                    @foreach ($competitions as $competition)
                                    <option value="{{ $competition }}">{{ $competition }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="editTeam1" class="form-label">Team 1 (Sekolah)</label>
                                <select name="team1_id" id="editTeam1" class="form-select" required>
                                    <option value="">Pilih Team 1</option>
                                    @foreach ($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="editTeam2" class="form-label">Team 2 (Sekolah)</label>
                                <select name="team2_id" id="editTeam2" class="form-select" required>
                                    <option value="">Pilih Team 2</option>
                                    @foreach ($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="editScore1" class="form-label">Score Team 1</label>
                                <input type="number" name="score_1" id="editScore1" class="form-control" min="0" required>
                            </div>

                            <div>
                                <label for="editScore2" class="form-label">Score Team 2</label>
                                <input type="number" name="score_2" id="editScore2" class="form-control" min="0" required>
                            </div>

                            <div>
                                <label for="editCompetitionType" class="form-label">Competition Type</label>
                                <select name="competition_type" id="editCompetitionType" class="form-select" required>
                                    <option value="">-- Pilih Competition Type --</option>
                                    @foreach ($competitionTypes as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="editPhase" class="form-label">Phase</label>
                                <select name="phase" id="editPhase" class="form-select" required>
                                    <option value="">Pilih Phase</option>
                                    @foreach ($phases as $phase)
                                    <option value="{{ $phase }}">{{ $phase }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-2">
                                <label for="editScoresheet" class="form-label">Upload Scoresheet (opsional)</label>
                                <input type="file" name="scoresheet" id="editScoresheet" accept=".pdf,.jpg,.jpeg,.png,.xls,.xlsx" class="form-control">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @endsection