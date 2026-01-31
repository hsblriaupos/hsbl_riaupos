<?php

namespace App\Http\Controllers\Publication;

use App\Http\Controllers\Controller;
use App\Models\MatchData;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PubMatchDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MatchData::query();
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('main_title', 'like', '%' . $search . '%')
                  ->orWhere('series_name', 'like', '%' . $search . '%')
                  ->orWhere('caption', 'like', '%' . $search . '%');
            });
        }
        
        // Series filter
        if ($request->filled('series')) {
            $query->where('series_name', $request->series);
        }
        
        // Year filter (from upload_date)
        if ($request->filled('year')) {
            $query->whereYear('upload_date', $request->year);
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Get unique series for filter dropdown
        $seriesList = MatchData::select('series_name')
            ->distinct()
            ->whereNotNull('series_name')
            ->orderBy('series_name')
            ->pluck('series_name');
        
        // Get available years from upload_date
        $availableYears = MatchData::selectRaw('YEAR(upload_date) as year')
            ->distinct()
            ->whereNotNull('upload_date')
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        // Pagination with per_page parameter
        $perPage = $request->get('per_page', 10);
        $schedules = $query->orderBy('upload_date', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->paginate($perPage);
        
        return view('publication.pub_schedule', compact(
            'schedules', 
            'seriesList', 
            'availableYears'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $eventId = $request->get('event_id');
        $event = null;
        
        if ($eventId) {
            $event = Event::find($eventId);
        }
        
        return view('publication.pub_schedule-create', compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'upload_date' => 'required|date',
            'main_title' => 'required|string|max:255',
            'caption' => 'nullable|string|max:500',
            'series_name' => 'required|string|max:100',
            'layout_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        try {
            DB::beginTransaction();
            
            $schedule = new MatchData();
            $schedule->upload_date = $request->upload_date;
            $schedule->main_title = $request->main_title;
            $schedule->caption = $request->caption;
            $schedule->series_name = $request->series_name;
            
            // PERBAIKAN: Set status dari action_type (default draft)
            $schedule->status = $request->get('action_type', 'draft');
            
            // PERBAIKAN: Handle image upload - SIMPAN DI PUBLIC/IMAGES/SCHEDULE
            if ($request->hasFile('layout_image')) {
                $image = $request->file('layout_image');
                $imageName = time() . '_' . preg_replace('/[^A-Za-z0-9\-\.]/', '', $image->getClientOriginalName());
                
                // Buat folder jika belum ada
                $directory = public_path('images/schedule');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                // Pindahkan file ke public/images/schedule
                $image->move($directory, $imageName);
                
                // Simpan path relatif untuk database
                $schedule->layout_image = 'images/schedule/' . $imageName;
            }
            
            // If linked to event
            if ($request->filled('event_id')) {
                $schedule->event_id = $request->event_id;
            }
            
            $schedule->save();
            
            DB::commit();
            
            return redirect()->route('admin.pub_schedule.index')
                ->with('success', 'Schedule created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error creating schedule: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $schedule = MatchData::findOrFail($id);
        
        return view('publication.pub_schedule-edit', compact('schedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'upload_date' => 'required|date',
            'main_title' => 'required|string|max:255',
            'caption' => 'nullable|string|max:500',
            'series_name' => 'required|string|max:100',
            'layout_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        try {
            DB::beginTransaction();
            
            $schedule = MatchData::findOrFail($id);
            
            // Cegah edit jika status done
            if ($schedule->status === 'done') {
                return redirect()->back()
                    ->with('error', 'Cannot edit schedule marked as done.');
            }
            
            // PERBAIKAN: Handle image upload - SIMPAN DI PUBLIC/IMAGES/SCHEDULE
            if ($request->hasFile('layout_image')) {
                // Delete old image if exists
                if ($schedule->layout_image && file_exists(public_path($schedule->layout_image))) {
                    unlink(public_path($schedule->layout_image));
                }
                
                $image = $request->file('layout_image');
                $imageName = time() . '_' . preg_replace('/[^A-Za-z0-9\-\.]/', '', $image->getClientOriginalName());
                
                // Buat folder jika belum ada
                $directory = public_path('images/schedule');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                // Pindahkan file ke public/images/schedule
                $image->move($directory, $imageName);
                
                // Simpan path relatif
                $schedule->layout_image = 'images/schedule/' . $imageName;
            }
            
            $schedule->upload_date = $request->upload_date;
            $schedule->main_title = $request->main_title;
            $schedule->caption = $request->caption;
            $schedule->series_name = $request->series_name;
            $schedule->save();
            
            DB::commit();
            
            return redirect()->route('admin.pub_schedule.index')
                ->with('success', 'Schedule updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error updating schedule: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            
            $schedule = MatchData::findOrFail($id);
            
            // PERBAIKAN: Delete image file jika ada (path public)
            if ($schedule->layout_image && file_exists(public_path($schedule->layout_image))) {
                unlink(public_path($schedule->layout_image));
            }
            
            $schedule->delete();
            
            DB::commit();
            
            return redirect()->route('admin.pub_schedule.index')
                ->with('success', 'Schedule deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error deleting schedule: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete schedules
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'selected' => 'required|array',
            'selected.*' => 'exists:match_data,id',
        ]);
        
        try {
            DB::beginTransaction();
            
            $schedules = MatchData::whereIn('id', $request->selected)->get();
            
            foreach ($schedules as $schedule) {
                // PERBAIKAN: Delete image file jika ada (path public)
                if ($schedule->layout_image && file_exists(public_path($schedule->layout_image))) {
                    unlink(public_path($schedule->layout_image));
                }
                $schedule->delete();
            }
            
            DB::commit();
            
            return redirect()->route('admin.pub_schedule.index')
                ->with('success', count($request->selected) . ' schedule(s) deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error deleting schedules: ' . $e->getMessage());
        }
    }

    /**
     * Publish a schedule
     */
    public function publish(string $id)
    {
        try {
            $schedule = MatchData::findOrFail($id);
            $schedule->status = 'publish';
            $schedule->save();
            
            return redirect()->back()
                ->with('success', 'Schedule published successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error publishing schedule: ' . $e->getMessage());
        }
    }

    /**
     * Unpublish a schedule
     */
    public function unpublish(string $id)
    {
        try {
            $schedule = MatchData::findOrFail($id);
            $schedule->status = 'draft';
            $schedule->save();
            
            return redirect()->back()
                ->with('success', 'Schedule unpublished successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error unpublishing schedule: ' . $e->getMessage());
        }
    }

    /**
     * Mark schedule as done
     */
    public function done(string $id)
    {
        try {
            $schedule = MatchData::findOrFail($id);
            $schedule->status = 'done';
            $schedule->save();
            
            return redirect()->back()
                ->with('success', 'Schedule marked as done.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error marking schedule as done: ' . $e->getMessage());
        }
    }

    /**
     * Bulk publish schedules
     */
    public function bulkPublish(Request $request)
    {
        $request->validate([
            'selected' => 'required|array',
            'selected.*' => 'exists:match_data,id',
        ]);
        
        try {
            MatchData::whereIn('id', $request->selected)
                    ->update(['status' => 'publish']);
            
            return redirect()->route('admin.pub_schedule.index')
                ->with('success', count($request->selected) . ' schedule(s) published successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error publishing schedules: ' . $e->getMessage());
        }
    }

    /**
     * Helper method untuk mendapatkan URL gambar yang benar
     */
    private function getImageUrl($imagePath)
    {
        if (!$imagePath) {
            return null;
        }
        
        // PERBAIKAN: Gunakan asset() langsung karena gambar di public/images/schedule
        return asset($imagePath);
    }
}