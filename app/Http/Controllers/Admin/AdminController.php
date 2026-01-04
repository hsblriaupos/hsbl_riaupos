<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\AddData;
use App\Models\School;
use App\Models\City;
use App\Models\Venue;
use App\Models\Award;



class AdminController extends Controller
{
    // jadikan method dashboard() jadi “index” untuk route /admin
    public function dashboard()
    {
        /**$totalImages   = Image::count();
        $todayUploads  = Image::whereDate('created_at', today())->count();
        $pendingImages = Image::where('published', false)->count();
        $recentImages  = Image::orderBy('created_at', 'desc')->take(5)->get();
         **/
        return view('admin.dashboard');
    }

    // ALL DATA
    public function allData()
    {
        $seasons = AddData::whereNotNull('season_name')
            ->where('season_name', '<>', '')
            ->distinct()
            ->pluck('season_name')
            ->sort();

        $series = AddData::whereNotNull('series_name')
            ->where('series_name', '<>', '')
            ->distinct()
            ->pluck('series_name')
            ->sort();

        $competitions = AddData::whereNotNull('competition')
            ->where('competition', '<>', '')
            ->distinct()
            ->pluck('competition')
            ->sort();

        $phases = AddData::whereNotNull('phase')
            ->where('phase', '<>', '')
            ->distinct()
            ->pluck('phase')
            ->sort();

        $competition_types = AddData::whereNotNull('competition_type')
            ->where('competition_type', '<>', '')
            ->distinct()
            ->pluck('competition_type')
            ->sort();

        return view('admin.all_data', compact('seasons', 'series', 'competitions', 'phases', 'competition_types'));
    }


    public function storeData(Request $request)
    {
        // 1️⃣ Validasi
        $validated = $request->validate([
            'season_name'      => 'nullable|string|max:255',
            'series_name'      => 'nullable|string|max:255',
            'competition'      => 'nullable|string|max:255',
            'phase'            => 'nullable|string|max:255',
            'competition_type' => 'nullable|string|max:255',
        ]);

        // 2️⃣ Pastikan minimal 1 field terisi
        if (collect($validated)->filter()->isEmpty()) {
            return redirect()
                ->route('admin.all_data')
                ->with('warning', 'Harus mengisi minimal salah satu field!');
        }

        // 3️⃣ Cek duplikasi (NULL-SAFE)
        $query = AddData::query();

        foreach ($validated as $column => $value) {
            if ($value === null || $value === '') {
                $query->whereNull($column);
            } else {
                $query->where($column, $value);
            }
        }

        if ($query->exists()) {
            return redirect()
                ->route('admin.all_data')
                ->with('warning', 'Data sudah ada!');
        }

        // 4️⃣ Simpan
        AddData::create($validated);

        return redirect()
            ->route('admin.all_data')
            ->with('success', 'Data berhasil ditambahkan!');
    }

    // CITY
    public function city()
    {
        $cities = City::all();
        return view('admin.all_data_city', compact('cities'));
    }
    public function storeCity(Request $request)
    {
        $data = $request->validate([
            'city_name' => 'nullable|string|max:255',
        ]);

        // Cek jika field kosong
        if (empty($data['city_name'])) {
            return redirect()->route('admin.all_data_city')->with('warning', 'Nama kota harus diisi!');
        }

        // Cek apakah kota sudah ada
        $exists = City::where('city_name', $data['city_name'])->exists();

        if ($exists) {
            return redirect()->route('admin.all_data_city')->with('warning', 'Kota sudah terdaftar!');
        }

        City::create($data);

        return redirect()->route('admin.all_data_city')->with('success', 'Kota berhasil ditambahkan!');
    }

    // SCHOOL MANAGEMENT
    public function school(Request $request)
    {
        $search = $request->input('search');
        $cityFilter = $request->input('city_filter');
        $categoryFilter = $request->input('category_filter');
        $typeFilter = $request->input('type_filter');
        $perPage = $request->get('per_page', 10);

        $schools = School::with('city')
            ->when($search, fn($query) => $query->where('school_name', 'like', "%{$search}%"))
            ->when($cityFilter, fn($query) => $query->where('city_id', $cityFilter))
            ->when($categoryFilter, fn($query) => $query->where('category_name', $categoryFilter))
            ->when($typeFilter, fn($query) => $query->where('type', $typeFilter))
            ->orderBy('school_name')
            ->paginate($perPage)
            ->withQueryString();

        $cities = City::orderBy('city_name')->get();
        $categories = ['SMA', 'SMK', 'MA'];
        $types = ['NEGERI', 'SWASTA'];

        return view('admin.all_data_school', compact('schools', 'cities', 'categories', 'types'));
    }

    public function storeSchool(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'school_name'   => 'required|string|max:255',
            'city_id'       => 'required|exists:cities,id',
            'category_name' => 'required|string|in:SMA,SMK,MA',
            'type'          => 'required|string|in:NEGERI,SWASTA',
        ]);

        // Cek duplikat
        $exists = School::where('school_name', $validated['school_name'])
            ->where('city_id', $validated['city_id'])
            ->exists();

        if ($exists) {
            return redirect()->route('admin.all_data_school')
                ->with('warning', 'Sekolah dengan nama dan kota tersebut sudah terdaftar.');
        }

        // Simpan data
        School::create($validated);

        return redirect()->route('admin.all_data_school')
            ->with('success', 'Sekolah berhasil ditambahkan.');
    }

    // Method untuk edit data (digunakan dalam modal)
    public function editData(Request $request)
    {
        $request->validate([
            'table' => 'required|string',
            'id' => 'required|integer',
        ]);

        $table = $request->table;
        $id = $request->id;

        // Jika table adalah schools
        if ($table === 'schools') {
            $validated = $request->validate([
                'school_name'   => 'required|string|max:255',
                'city_id'       => 'required|exists:cities,id',
                'category_name' => 'required|string|in:SMA,SMK,MA',
                'type'          => 'required|string|in:NEGERI,SWASTA',
            ]);

            $school = School::findOrFail($id);

            // Cek duplikat (kecuali untuk record yang sama)
            $exists = School::where('school_name', $validated['school_name'])
                ->where('city_id', $validated['city_id'])
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return redirect()->route('admin.all_data_school')
                    ->with('warning', 'Sekolah dengan nama dan kota tersebut sudah terdaftar.');
            }

            $school->update($validated);

            return redirect()->route('admin.all_data_school')
                ->with('success', 'Data sekolah berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Tabel tidak valid.');
    }

    // Method untuk delete data
    public function deleteData(Request $request)
    {
        $request->validate([
            'table' => 'required|string',
            'id' => 'required|integer',
        ]);

        $table = $request->table;
        $id = $request->id;

        if ($table === 'schools') {
            $school = School::findOrFail($id);
            $school->delete();

            return redirect()->route('admin.all_data_school')
                ->with('success', 'Data sekolah berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Tabel tidak valid.');
    }

    // Optional: Method untuk export data
    public function exportSchool()
    {
        // Logika export data sekolah ke Excel/CSV
        // Anda bisa menggunakan package seperti Maatwebsite/Laravel-Excel
        return response()->json(['message' => 'Export feature coming soon']);
    }



    // VENUE 
    public function venue(Request $request)
    {
        $city_id = $request->get('city_id');
        $perPage = $request->get('per_page', 10); // Default 10 data per halaman

        $venues = Venue::when($city_id, function ($query) use ($city_id) {
            return $query->where('city_id', $city_id);
        })
            ->paginate($perPage);

        $cities = City::all(); // Asumsikan sudah ada model City untuk daftar kota

        return view('admin.all_data_venue', compact('venues', 'cities'));
    }
    public function storeVenue(Request $request)
    {
        $request->validate([
            'venue_name' => 'nullable|string|max:255',
            'city_id'    => 'nullable|exists:cities,id',
            'location'   => 'nullable|string|max:255',
            'layout'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $venueName = $request->input('venue_name');
        $cityId    = $request->input('city_id');
        $location  = $request->input('location');

        // Validasi kombinasi wajib: venue_name & city_id
        if (empty($venueName) && empty($cityId)) {
            return redirect()->route('admin.all_data_venue')->with('warning', 'Harus mengisi minimal nama venue dan kota!');
        }

        if (empty($venueName) || empty($cityId)) {
            return redirect()->route('admin.all_data_venue')->with('warning', 'Nama venue dan kota harus diisi!');
        }

        // Cek duplikasi
        $exists = Venue::where('venue_name', $venueName)->where('city_id', $cityId)->exists();
        if ($exists) {
            return redirect()->route('admin.all_data_venue')->with('warning', 'Venue dengan nama dan kota tersebut sudah terdaftar.');
        }

        // Upload layout jika ada
        $layoutFileName = null;
        if ($request->hasFile('layout')) {
            $layoutFileName = $request->file('layout')->store('venue_layouts', 'public');
        }

        // Simpan data ke database
        Venue::create([
            'venue_name' => $venueName,
            'city_id'    => $cityId,
            'location'   => $location,
            'layout'     => $layoutFileName,
        ]);

        return redirect()->route('admin.all_data_venue')->with('success', 'Venue berhasil ditambahkan.');
    }

    // AWARD
    public function award()
    {
        $awardTypes = Award::whereNotNull('award_type')->select('award_type')->distinct()->pluck('award_type');
        $awardCategories = Award::whereNotNull('category')->select('category')->distinct()->pluck('category');

        return view('admin.all_data_award', compact('awardTypes', 'awardCategories'));
    }

    public function storeAward(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            'award_type' => 'nullable|string|max:255',
            'category'   => 'nullable|string|max:255',
        ]);

        // Kalau dua-duanya kosong, balikin warning
        if (is_null($data['award_type']) && is_null($data['category'])) {
            return redirect()->route('admin.all_data_award')->with('warning', 'Harus mengisi minimal salah satu field!');
        }

        // Cek apakah data yang sama sudah ada
        $query = Award::query();

        if (!is_null($data['award_type'])) {
            $query->where('award_type', $data['award_type']);
        } else {
            $query->whereNull('award_type');
        }

        if (!is_null($data['category'])) {
            $query->where('category', $data['category']);
        } else {
            $query->whereNull('category');
        }

        if ($query->exists()) {
            return redirect()->route('admin.all_data_award')->with('warning', 'Award dengan kombinasi ini sudah ada!');
        }

        // Simpan
        Award::create($data);

        return redirect()->route('admin.all_data_award')->with('success', 'Award berhasil ditambahkan.');
    }



    /*public function logout(Request $request)
    {
        auth()->logout();
        return redirect()->route('login');
    }
        */
}
