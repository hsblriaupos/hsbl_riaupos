<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\AddData;
use App\Models\School;
use App\Models\City;
use App\Models\Venue;
use App\Models\Award;

class DataActionController extends Controller
{
    private $map = [
        'school' => [
            'table' => 'schools',
            'cols'  => ['id', 'school_name', 'city_id', 'category_name', 'type'],
        ],
        'award' => [
            'table' => 'awards',
            'cols'  => ['id', 'award_type', 'category'],
        ],
        'venue' => [
            'table' => 'venue',
            'cols'  => ['id', 'venue_name', 'city_id', 'location', 'layout'],
        ],
        'match' => [
            'table' => 'match_data',
            'cols'  => ['id', 'upload_date', 'main_title', 'caption', 'layout_image', 'status'],
        ],
        'match_result' => [
            'table' => 'match_results',
            'cols'  => ['id', 'match_id', 'team1_score', 'team2_score', 'match_date', 'status'],
        ],
        'camper' => [
            'table' => 'camper',
            'cols'  => ['id', 'camper_name', 'school_id'],
        ],
        'city' => [
            'table' => 'cities',
            'cols'  => ['id', 'city_name'],
        ],
        'event' => [
            'table' => 'events_data',
            'cols'  => ['id', 'event_name', 'start_date', 'start_time', 'end_date', 'end_time'],
        ],
    ];

    public function index(Request $request, string $type)
    {
        if (!isset($this->map[$type])) {
            abort(404, 'Tipe data tidak valid.');
        }

        $table  = $this->map[$type]['table'];
        $cols   = $this->map[$type]['cols'];
        $search = $request->input('search');

        $query = DB::table($table);
        if ($search) {
            $query->where($cols[1], 'like', "%{$search}%");
        }

        $rows = $query->orderBy($cols[0])->get();

        return view("admin.all_data_{$type}", [
            'rows'   => $rows,
            'type'   => $type,
            'search' => $search,
        ]);
    }

    // ========== EDIT METHOD UNTUK SEMUA DATA ==========
    public function edit(Request $request)
    {
        Log::info('EDIT REQUEST:', $request->all());

        $table = $request->input('table');

        // ========== KHUSUS ADD_DATA ==========
        if ($table === 'add_data') {
            try {
                $type = $request->input('type');
                $old = trim($request->input('old_value'));
                $new = trim($request->input('new_value'));

                Log::info('Editing add_data:', ['type' => $type, 'old' => $old, 'new' => $new]);

                if (!$type || !$old) {
                    return redirect()->route('admin.all_data')->with('error', 'Data tidak lengkap!');
                }

                // Mapping type ke nama kolom di database
                $colMap = [
                    'season' => 'season_name',
                    'series' => 'series_name',
                    'competition' => 'competition',
                    'phase' => 'phase',
                    'competition_type' => 'competition_type',
                    'competition type' => 'competition_type',
                    'competition-type' => 'competition_type',
                ];

                $typeKey = strtolower(str_replace([' ', '-'], '_', $type));

                if (!isset($colMap[$typeKey])) {
                    return redirect()->route('admin.all_data')->with('error', 'Tipe data tidak valid!');
                }

                $column = $colMap[$typeKey];

                // Jika new value kosong, set menjadi NULL
                if ($new === '') {
                    $new = null;
                }

                // Cek apakah data ada
                $exists = DB::table('add_data')->where($column, $old)->exists();
                if (!$exists) {
                    return redirect()->route('admin.all_data')->with('error', 'Data tidak ditemukan!');
                }

                // Cek jika nilai baru sudah ada (kecuali sama dengan yang lama)
                if ($new !== null && $new !== $old) {
                    $alreadyExists = DB::table('add_data')->where($column, $new)->exists();
                    if ($alreadyExists) {
                        return redirect()->route('admin.all_data')->with('warning', 'Nilai "' . $new . '" sudah ada!');
                    }
                }

                // Update data
                $updated = DB::table('add_data')
                    ->where($column, $old)
                    ->update([$column => $new]);

                if ($updated > 0) {
                    return redirect()->route('admin.all_data')->with('success', 'Data berhasil diubah');
                } else {
                    return redirect()->route('admin.all_data')->with('warning', 'Tidak ada data yang berubah.');
                }
            } catch (\Exception $e) {
                Log::error('Error editing add_data:', ['error' => $e->getMessage()]);
                return redirect()->route('admin.all_data')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }

        // ========== KHUSUS CITY ==========
        if ($table === 'cities') {
            try {
                $id = $request->input('id');
                $newCityName = trim($request->input('new_value'));

                Log::info('Editing city:', ['id' => $id, 'new_value' => $newCityName]);

                if (!$id || !$newCityName) {
                    return redirect()->route('admin.all_data_city')->with('error', 'Data tidak lengkap!');
                }

                // Cek apakah nama kota baru sudah ada
                $exists = City::where('city_name', $newCityName)
                    ->where('id', '!=', $id)
                    ->exists();

                if ($exists) {
                    return redirect()->route('admin.all_data_city')->with('warning', 'Kota "' . $newCityName . '" sudah ada!');
                }

                // Update kota
                $city = City::findOrFail($id);
                $city->city_name = $newCityName;
                $city->save();

                return redirect()->route('admin.all_data_city')->with('success', 'Data kota berhasil diubah');
            } catch (\Exception $e) {
                Log::error('Error editing city:', ['error' => $e->getMessage()]);
                return redirect()->route('admin.all_data_city')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }

        // ========== KHUSUS SCHOOL ==========
        if ($table === 'schools') {
            try {
                $id = $request->input('id');
                $data = $request->validate([
                    'school_name'   => 'required|string|max:255',
                    'city_id'       => 'required|exists:cities,id',
                    'category_name' => 'required|string|in:SMA,SMK,MA',
                    'type'          => 'required|string|in:NEGERI,SWASTA',
                ]);

                Log::info('Editing school:', ['id' => $id, 'data' => $data]);

                $school = School::findOrFail($id);

                // Cek duplikat (kecuali untuk record yang sama)
                $exists = School::where('school_name', $data['school_name'])
                    ->where('city_id', $data['city_id'])
                    ->where('id', '!=', $id)
                    ->exists();

                if ($exists) {
                    return redirect()->route('admin.all_data_school')
                        ->with('warning', 'Sekolah dengan nama dan kota tersebut sudah terdaftar.');
                }

                $school->update($data);

                return redirect()->route('admin.all_data_school')->with('success', 'Data sekolah berhasil diubah');
            } catch (\Exception $e) {
                Log::error('Error editing school:', ['error' => $e->getMessage()]);
                return redirect()->route('admin.all_data_school')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }

        // ========== KHUSUS AWARD ==========
        if ($table === 'awards') {
            try {
                $field = $request->input('field');
                $originalValue = $request->input('original_value');
                $newValue = trim($request->input('new_value'));

                Log::info('Editing award:', ['field' => $field, 'old' => $originalValue, 'new' => $newValue]);

                // Validasi field
                if (!in_array($field, ['award_type', 'category'])) {
                    return redirect()->route('admin.all_data_award')->with('error', 'Kolom tidak valid!');
                }

                if (!$newValue) {
                    return redirect()->route('admin.all_data_award')->with('error', 'Nilai baru tidak boleh kosong!');
                }

                // Cek apakah nilai baru sudah ada
                $exists = Award::where($field, $newValue)->exists();
                if ($exists) {
                    return redirect()->route('admin.all_data_award')->with('warning', 'Nilai "' . $newValue . '" sudah ada di database!');
                }

                // Update semua record yang memiliki nilai tersebut
                $updated = Award::where($field, $originalValue)->update([$field => $newValue]);

                if ($updated > 0) {
                    return redirect()->route('admin.all_data_award')->with('success', 'Data award berhasil diubah');
                } else {
                    return redirect()->route('admin.all_data_award')->with('warning', 'Tidak ada data yang berubah.');
                }
            } catch (\Exception $e) {
                Log::error('Error editing award:', ['error' => $e->getMessage()]);
                return redirect()->route('admin.all_data_award')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }

        // ========== KHUSUS VENUE ==========
        if ($table === 'venue') {
            try {
                $id = $request->input('id');
                $data = $request->except(['_token', 'table', 'id', 'layout']);

                Log::info('Editing venue:', ['id' => $id, 'data' => $data]);

                if ($request->hasFile('layout')) {
                    $oldLayout = DB::table('venue')->where('id', $id)->value('layout');
                    if ($oldLayout && Storage::disk('public')->exists($oldLayout)) {
                        Storage::disk('public')->delete($oldLayout);
                    }

                    $layoutPath = $request->file('layout')->store('venue_layouts', 'public');
                    $data['layout'] = $layoutPath;
                }

                DB::table('venue')->where('id', $id)->update($data);

                return redirect()->route('admin.all_data_venue')->with('success', 'Data venue berhasil diubah');
            } catch (\Exception $e) {
                Log::error('Error editing venue:', ['error' => $e->getMessage()]);
                return redirect()->route('admin.all_data_venue')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }

        // ========== DEFAULT UNTUK TABEL LAIN ==========
        try {
            $id = $request->input('id');
            $data = $request->except(['_token', 'table', 'id']);

            Log::info('Editing general:', ['table' => $table, 'id' => $id, 'data' => $data]);

            // Translate table alias to real name
            if (array_key_exists($table, $this->map)) {
                $table = $this->map[$table]['table'];
            }

            // --- MATCH DATA
            if ($table === 'match_data') {
                $match = DB::table($table)->where('id', $id)->first();
                if ($match && strtolower($match->status) === 'done') {
                    return redirect()->back()->with('warning', 'Jadwal yang sudah selesai tidak dapat diubah.');
                }

                if ($request->hasFile('layout_image')) {
                    $request->validate([
                        'layout_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                    ]);

                    $oldImage = DB::table($table)->where('id', $id)->value('layout_image');
                    if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }

                    $imagePath = $request->file('layout_image')->store('match_layouts', 'public');
                    $data['layout_image'] = $imagePath;
                }
            }

            // --- MATCH_RESULTS
            if ($table === 'match_results') {
                if (isset($data['date'])) {
                    $data['match_date'] = $data['date'];
                    unset($data['date']);
                }

                if ($request->hasFile('scoresheet')) {
                    $oldFile = DB::table($table)->where('id', $id)->value('scoresheet');
                    if ($oldFile) {
                        Storage::disk('public')->delete($oldFile);
                    }

                    $filePath = $request->file('scoresheet')->store('match_results', 'public');
                    $data['scoresheet'] = $filePath;
                }
            }

            // --- EVENT
            if ($table === 'events_data') {
                if ($request->has('start_time')) {
                    $startTime = Carbon::createFromFormat('H:i', $request->input('start_time'), 'Asia/Jakarta')
                        ->setTimezone('UTC')
                        ->format('H:i:s');
                    $data['start_time'] = $startTime;
                }
                if ($request->has('end_time')) {
                    $endTime = Carbon::createFromFormat('H:i', $request->input('end_time'), 'Asia/Jakarta')
                        ->setTimezone('UTC')
                        ->format('H:i:s');
                    $data['end_time'] = $endTime;
                }
            }

            DB::table($table)->where('id', $id)->update($data);
            return redirect()->back()->with('success', 'Data berhasil diubah');
        } catch (\Exception $e) {
            Log::error('Error editing data:', ['error' => $e->getMessage(), 'table' => $table]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ========== DELETE METHOD UNTUK SEMUA DATA ==========
    public function delete(Request $request)
    {
        Log::info('DELETE REQUEST:', $request->all());

        $table = $request->input('table');

        // ========== KHUSUS ADD_DATA ==========
        if ($table === 'add_data') {
            try {
                $type = $request->input('type');
                $values = $request->input('selected');

                Log::info('Deleting add_data:', ['type' => $type, 'values' => $values]);

                if (!$type || empty($values)) {
                    return redirect()->route('admin.all_data')->with('error', 'Data tidak lengkap!');
                }

                // Mapping type
                $colMap = [
                    'season' => 'season_name',
                    'series' => 'series_name',
                    'competition' => 'competition',
                    'phase' => 'phase',
                    'competition_type' => 'competition_type',
                    'competition type' => 'competition_type',
                    'competition-type' => 'competition_type',
                ];

                $typeKey = strtolower(str_replace([' ', '-'], '_', $type));

                if (!isset($colMap[$typeKey])) {
                    return redirect()->route('admin.all_data')->with('error', 'Tipe data tidak valid!');
                }

                $column = $colMap[$typeKey];

                $deletedCount = 0;
                $errors = [];

                foreach ($values as $value) {
                    $value = trim($value);
                    if (!empty($value)) {
                        try {
                            $deleted = DB::table('add_data')
                                ->where($column, $value)
                                ->delete();

                            if ($deleted > 0) {
                                $deletedCount += $deleted;
                            }
                        } catch (\Exception $e) {
                            $errors[] = 'Gagal menghapus "' . $value . '": ' . $e->getMessage();
                        }
                    }
                }

                if (!empty($errors)) {
                    return redirect()->route('admin.all_data')->with('error', implode('<br>', $errors));
                }

                if ($deletedCount > 0) {
                    return redirect()->route('admin.all_data')->with('success', 'Data berhasil dihapus');
                } else {
                    return redirect()->route('admin.all_data')->with('warning', 'Tidak ada data yang terhapus.');
                }
            } catch (\Exception $e) {
                Log::error('Error deleting add_data:', ['error' => $e->getMessage()]);
                return redirect()->route('admin.all_data')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }

        // ========== KHUSUS CITY ==========
        if ($table === 'cities') {
            try {
                $id = $request->input('id');

                Log::info('Deleting city:', ['id' => $id]);

                if (!$id) {
                    return redirect()->route('admin.all_data_city')->with('error', 'ID tidak valid!');
                }

                // Check if city is used in other tables
                $usedInSchools = DB::table('schools')->where('city_id', $id)->exists();
                $usedInVenues = DB::table('venue')->where('city_id', $id)->exists();

                if ($usedInSchools || $usedInVenues) {
                    return redirect()->route('admin.all_data_city')->with('error', 'Kota tidak dapat dihapus karena masih digunakan di data lain!');
                }

                $deleted = DB::table('cities')->where('id', $id)->delete();

                if ($deleted > 0) {
                    return redirect()->route('admin.all_data_city')->with('success', 'Data kota berhasil dihapus');
                } else {
                    return redirect()->route('admin.all_data_city')->with('warning', 'Kota tidak ditemukan.');
                }
            } catch (\Exception $e) {
                Log::error('Error deleting city:', ['error' => $e->getMessage()]);
                return redirect()->route('admin.all_data_city')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }

        // ========== KHUSUS SCHOOL ==========
        if ($table === 'schools') {
            try {
                $id = $request->input('id');

                Log::info('Deleting school:', ['id' => $id]);

                $school = School::findOrFail($id);
                $school->delete();

                return redirect()->route('admin.all_data_school')->with('success', 'Data sekolah berhasil dihapus');
            } catch (\Exception $e) {
                Log::error('Error deleting school:', ['error' => $e->getMessage()]);
                return redirect()->route('admin.all_data_school')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }

        // ========== KHUSUS AWARD ==========
        if ($table === 'awards') {
            try {
                $field = $request->input('field');
                $value = $request->input('value');

                Log::info('Deleting award:', ['field' => $field, 'value' => $value]);

                // Validasi field
                if (!in_array($field, ['award_type', 'category'])) {
                    return redirect()->route('admin.all_data_award')->with('error', 'Kolom tidak valid!');
                }

                // Hapus semua record yang memiliki nilai tersebut
                $deleted = Award::where($field, $value)->delete();

                if ($deleted > 0) {
                    return redirect()->route('admin.all_data_award')->with('success', 'Data award berhasil dihapus');
                } else {
                    return redirect()->route('admin.all_data_award')->with('warning', 'Tidak ada data yang dihapus.');
                }
            } catch (\Exception $e) {
                Log::error('Error deleting award:', ['error' => $e->getMessage()]);
                return redirect()->route('admin.all_data_award')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }

        // ========== DEFAULT UNTUK TABEL LAIN ==========
        try {
            $id = $request->input('id');
            $field = $request->input('field');
            $fieldValue = $request->input('value');

            Log::info('Deleting general:', ['table' => $table, 'id' => $id, 'field' => $field, 'value' => $fieldValue]);

            // Translate table alias to real name
            if (array_key_exists($table, $this->map)) {
                $table = $this->map[$table]['table'];
            }

            $allowedTables = array_column($this->map, 'table');
            if (!in_array($table, $allowedTables)) {
                return redirect()->back()->with('error', 'Tabel tidak valid.');
            }

            // Delete by field and value
            if (!empty($field) && !empty($fieldValue)) {
                DB::table($table)->where($field, $fieldValue)->delete();
                return redirect()->back()->with('success', 'Data berhasil dihapus');
            }

            // Delete by ID
            if (!empty($id)) {
                // Delete file jika ada, khusus beberapa tabel
                if ($table === 'match_data') {
                    $filename = DB::table($table)->where('id', $id)->value('layout_image');
                    if ($filename) {
                        Storage::disk('public')->delete($filename);
                    }
                }

                if ($table === 'venue') {
                    $filename = DB::table($table)->where('id', $id)->value('layout');
                    if ($filename) {
                        Storage::disk('public')->delete($filename);
                    }
                }

                if ($table === 'match_results') {
                    $file = DB::table($table)->where('id', $id)->value('scoresheet');
                    if ($file) {
                        Storage::disk('public')->delete($file);
                    }
                }

                DB::table($table)->where('id', $id)->delete();
                return redirect()->back()->with('success', 'Data berhasil dihapus');
            }

            return redirect()->back()->with('error', 'Parameter tidak lengkap atau tidak valid.');
        } catch (\Exception $e) {
            Log::error('Error deleting data:', ['error' => $e->getMessage(), 'table' => $table]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export data ke CSV.
     */
    public function export(string $type)
    {
        if (!isset($this->map[$type])) {
            abort(404, 'Tipe data tidak valid.');
        }

        $table = $this->map[$type]['table'];
        $cols = $this->map[$type]['cols'];

        $data = DB::table($table)->select($cols)->get();

        $filename = "{$type}_data_" . date('Ymd_His') . ".csv";

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, $cols); // header

        foreach ($data as $row) {
            fputcsv($handle, (array) $row);
        }

        rewind($handle);
        $contents = stream_get_contents($handle);
        fclose($handle);

        return Response::make($contents, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }
}
