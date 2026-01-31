<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $seriesList = News::pluck('series')
                          ->unique()
                          ->sort()
                          ->toArray();

        $query = News::query();

        if ($request->filled('search')) {
            $kw = $request->search;
            $query->where(fn($q) =>
                $q->where('title', 'like', "%{$kw}%")
                  ->orWhere('content', 'like', "%{$kw}%")
            );
        }

        if ($request->filled('series')) {
            $query->where('series', $request->series);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Ambil parameter per_page dari request, default 10
        $perPage = $request->get('per_page', 10);
        
        // Validasi nilai per_page agar hanya angka yang valid
        $validPerPage = in_array((int)$perPage, [10, 25, 50, 100]) ? (int)$perPage : 10;

        // Paginate dengan jumlah per page yang dipilih
        $news = $query->latest()
                      ->paginate($validPerPage)
                      ->withQueryString();

        return view('admin.media.news.news_list', compact('news', 'seriesList'));
    }

    public function create()
    {
        $seriesList = News::pluck('series')
                          ->unique()
                          ->sort()
                          ->toArray();

        return view('admin.media.news.news_create', compact('seriesList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'series'     => 'required|string|max:100',
            'title'      => 'required|string|max:255',
            'posted_by'  => 'required|string|max:100',
            'status'     => 'required|in:draft,view',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'content'    => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time()
                  . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                  . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/news'), $name);
            $data['image'] = 'images/news/' . $name;
        }

        News::create($data);

        $statusMessage = $data['status'] == 'draft' ? 'saved as draft' : 'published (view)';
        
        return redirect()
            ->route('admin.news.index')
            ->with('success', "News has been {$statusMessage} successfully.");
    }

    public function edit(int $id)
    {
        $news       = News::findOrFail($id);
        $seriesList = News::pluck('series')
                          ->unique()
                          ->sort()
                          ->toArray();

        return view('admin.media.news.news_edit', compact('news', 'seriesList'));
    }

    public function update(Request $request, int $id)
    {
        $news = News::findOrFail($id);

        $data = $request->validate([
            'series'     => 'required|string|max:100',
            'title'      => 'required|string|max:255',
            'posted_by'  => 'required|string|max:100',
            'status'     => 'required|in:draft,view',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'content'    => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time()
                  . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                  . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/news'), $name);
            $data['image'] = 'images/news/' . $name;
        }

        $news->update($data);

        $statusMessage = $data['status'] == 'draft' ? 'updated as draft' : 'published (view)';
        
        return redirect()
            ->route('admin.news.index')
            ->with('success', "News has been {$statusMessage} successfully.");
    }

    public function destroy(int $id)
    {
        $news = News::findOrFail($id);
        $news->delete();

        return back()->with('success', 'News has been deleted successfully.');
    }

    /**
     * Bulk delete news items
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'selected' => 'required|array',
            'selected.*' => 'exists:media_news,id'
        ]);
        
        $count = count($request->selected);
        
        // Delete selected news items
        News::whereIn('id', $request->selected)->delete();
        
        return redirect()->route('admin.news.index')
            ->with('success', $count . ' news ' . ($count > 1 ? 'items' : 'item') . ' deleted successfully.');
    }
}