@extends('admin.layouts.app')
@section('title', 'Detail Camper')
@section('content')

@push('scripts')
<script>
    function enableEditMode() {
        const inputs = document.querySelectorAll('[data-editable]');
        inputs.forEach(input => {
            input.removeAttribute('readonly');
            input.removeAttribute('disabled');
            input.classList.remove('bg-gray-100');
        });

        document.getElementById('edit-btn').classList.add('hidden');
        document.getElementById('save-btn').classList.remove('hidden');
    }
</script>
@endpush

<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        🧑‍🎓 Detail Camper: <span class="text-blue-600">{{ $camper->player->name }}</span>
    </h1>

    <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-200">
        <div class="flex justify-start mb-4">
            <a href="{{ route('admin.camper_team') }}" class="inline-flex items-center gap-2 text-sm text-gray-700 hover:text-blue-600 font-medium">
                ← Back 
            </a>
        </div>

        <form action="{{ route('admin.camper.update', $camper->player->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            {{-- Static Info --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-base font-semibold text-gray-700 mb-1">Name</label>
                    <input type="text" value="{{ $camper->player->name }}" class="form-input w-full  rounded-lg border border-gray-300" readonly>
                </div>

                <div>
                    <label class="block text-base font-semibold text-gray-700 mb-1">School</label>
                    <input type="text" value="{{ $camper->player->schoolData->school_name ?? '-' }}" class="form-input w-full  rounded-lg border border-gray-300" readonly>
                </div>
            </div>

            {{-- Editable Fields --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                $fields = [
                'NIK' => 'nik',
                'Birth Date' => 'birthdate',
                'Phone' => 'phone',
                'Grade' => 'grade',
                'Height (cm)' => 'height',
                'Weight (kg)' => 'weight',
                'T-Shirt Size' => 'tshirt_size',
                'Shoe Size' => 'shoes_size',
                'Position' => 'basketball_position'
                ];
                @endphp

                {{-- Dynamic Input Fields --}}
                @foreach($fields as $label => $field)
                <div>
                    <label class="block text-base font-medium text-gray-700 mb-1">{{ $label }}</label>
                    <input
                        type="{{ $field === 'birthdate' ? 'date' : ($field === 'height' || $field === 'weight' ? 'number' : 'text') }}"
                        name="{{ $field }}"
                        value="{{ $camper->player->$field }}"
                        data-editable
                        class="form-input w-full border border-gray-300 rounded-lg "
                        readonly>
                </div>
                @endforeach

                {{-- Gender Field --}}
                <div>
                    <label class="block text-base font-medium text-gray-700 mb-1">Gender</label>
                    <select name="gender" data-editable class="form-input w-full border border-gray-300 rounded-lg " disabled>
                        <option value="Laki-laki" {{ $camper->player->gender == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $camper->player->gender == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-4 mt-8">
                <button type="button" onclick="enableEditMode()" id="edit-btn" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg shadow-md">
                    ✏️ Edit
                </button>
                <button type="submit" id="save-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-md hidden">
                    💾 Save
                </button>
            </div>
        </form>
    </div>
</div>

@endsection