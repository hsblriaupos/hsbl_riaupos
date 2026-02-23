<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AddData;
use App\Models\School;
use App\Models\City;
use App\Models\Venue;
use App\Models\Award;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    // ========== ADD_DATA ==========
    public function allData()
    {
        $getDistinctData = function ($column) {
            return AddData::whereNotNull($column)
                ->where($column, '<>', '')
                ->distinct()
                ->orderBy($column)
                ->pluck($column);
        };

        $seasons = $getDistinctData('season_name');
        $series = $getDistinctData('series_name');
        $competitions = $getDistinctData('competition');
        $phases = $getDistinctData('phase');
        $competition_types = $getDistinctData('competition_type');

        return view('admin.all_data', [
            'seasons' => $seasons,
            'series' => $series,
            'competitions' => $competitions,
            'phases' => $phases,
            'competition_types' => $competition_types,
        ]);
    }

    public function storeData(Request $request)
    {
        $validated = $request->validate([
            'season_name'      => 'nullable|string|max:255',
            'series_name'      => 'nullable|string|max:255',
            'competition'      => 'nullable|string|max:255',
            'phase'            => 'nullable|string|max:255',
            'competition_type' => 'nullable|string|max:255',
        ]);

        $cleanedData = [];
        foreach ($validated as $key => $value) {
            $cleanedData[$key] = (trim($value ?? '') === '') ? null : trim($value);
        }

        $filledFields = array_filter($cleanedData, function ($value) {
            return $value !== null;
        });

        if (empty($filledFields)) {
            return redirect()
                ->route('admin.all_data')
                ->with('warning', 'Harus mengisi minimal salah satu field!');
        }

        $query = AddData::query();
        foreach ($cleanedData as $column => $value) {
            if ($value !== null) {
                $query->where($column, $value);
            }
        }

        if ($query->exists()) {
            return redirect()
                ->route('admin.all_data')
                ->with('warning', 'Data sudah ada!');
        }

        try {
            AddData::create($cleanedData);
            return redirect()
                ->route('admin.all_data')
                ->with('success', 'Data berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.all_data')
                ->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    // ========== CITY ==========
    public function city()
    {
        $cities = City::all();
        return view('admin.all_data_city', compact('cities'));
    }

    public function storeCity(Request $request)
    {
        $data = $request->validate([
            'city_name' => 'required|string|max:255',
        ]);

        $exists = City::where('city_name', $data['city_name'])->exists();
        if ($exists) {
            return redirect()->route('admin.all_data_city')->with('warning', 'Kota sudah terdaftar!');
        }

        City::create($data);
        return redirect()->route('admin.all_data_city')->with('success', 'Kota berhasil ditambahkan!');
    }

    // ========== SCHOOL ==========
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

        $schools->appends(request()->query());

        $cities = City::orderBy('city_name')->get();
        $categories = ['SMA', 'SMK', 'MA'];
        $types = ['NEGERI', 'SWASTA'];

        return view('admin.all_data_school', compact('schools', 'cities', 'categories', 'types'));
    }

    public function storeSchool(Request $request)
    {
        $validated = $request->validate([
            'school_name'   => 'required|string|max:255',
            'city_id'       => 'required|exists:cities,id',
            'category_name' => 'required|string|in:SMA,SMK,MA',
            'type'          => 'required|string|in:NEGERI,SWASTA',
        ]);

        $exists = School::where('school_name', $validated['school_name'])
            ->where('city_id', $validated['city_id'])
            ->exists();

        if ($exists) {
            return redirect()->route('admin.all_data_school')
                ->with('warning', 'Sekolah dengan nama dan kota tersebut sudah terdaftar.');
        }

        School::create($validated);
        return redirect()->route('admin.all_data_school')
            ->with('success', 'Sekolah berhasil ditambahkan.');
    }

    // ========== VENUE ==========
    public function venue(Request $request)
    {
        $city_id = $request->get('city_id');
        $perPage = $request->get('per_page', 10);

        $venues = Venue::when($city_id, function ($query) use ($city_id) {
            return $query->where('city_id', $city_id);
        })
            ->paginate($perPage);

        $cities = City::all();
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

        if (empty($venueName) && empty($cityId)) {
            return redirect()->route('admin.all_data_venue')->with('warning', 'Harus mengisi minimal nama venue dan kota!');
        }

        if (empty($venueName) || empty($cityId)) {
            return redirect()->route('admin.all_data_venue')->with('warning', 'Nama venue dan kota harus diisi!');
        }

        $exists = Venue::where('venue_name', $venueName)->where('city_id', $cityId)->exists();
        if ($exists) {
            return redirect()->route('admin.all_data_venue')->with('warning', 'Venue dengan nama dan kota tersebut sudah terdaftar.');
        }

        $layoutFileName = null;
        if ($request->hasFile('layout')) {
            $layoutFileName = $request->file('layout')->store('venue_layouts', 'public');
        }

        Venue::create([
            'venue_name' => $venueName,
            'city_id'    => $cityId,
            'location'   => $request->input('location'),
            'layout'     => $layoutFileName,
        ]);

        return redirect()->route('admin.all_data_venue')->with('success', 'Venue berhasil ditambahkan.');
    }

    // ========== AWARD ==========
    public function award()
    {
        $awardTypes = Award::whereNotNull('award_type')->select('award_type')->distinct()->pluck('award_type');
        $awardCategories = Award::whereNotNull('category')->select('category')->distinct()->pluck('category');

        return view('admin.all_data_award', compact('awardTypes', 'awardCategories'));
    }

    public function storeAward(Request $request)
    {
        $data = $request->validate([
            'award_type' => 'nullable|string|max:255',
            'category'   => 'nullable|string|max:255',
        ]);

        if (is_null($data['award_type']) && is_null($data['category'])) {
            return redirect()->route('admin.all_data_award')->with('warning', 'Harus mengisi minimal salah satu field!');
        }

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

        Award::create($data);
        return redirect()->route('admin.all_data_award')->with('success', 'Award berhasil ditambahkan.');
    }
}
