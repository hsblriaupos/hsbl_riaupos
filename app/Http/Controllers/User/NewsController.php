<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /**
     * Tampilkan daftar berita dengan opsi search, filter series, dan pagination.
     */
    public function index(Request $request)
    {
        // 1. Ambil daftar series unik
        $seriesList = News::where('status', 'view')
            ->pluck('series')
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        // 2. Bangun query: hanya berita yang dipublikasikan (status = view)
        $query = News::where('status', 'view');

        // 3. Tambahkan pencarian jika ada keyword
        if ($request->filled('search')) {
            $kw = $request->search;
            $query->where(function ($q) use ($kw) {
                $q->where('title', 'like', "%{$kw}%")
                  ->orWhere('content', 'like', "%{$kw}%");
            });
        }

        // 4. Filter series jika ada
        if ($request->filled('series')) {
            $query->where('series', $request->series);
        }

        // 5. Ambil data berita terbaru, paginate
        $news = $query->latest()
                      ->paginate(8)
                      ->withQueryString(); // Gunakan withQueryString() untuk menjaga parameter URL

        return view('user.media.news.news_list', compact('news', 'seriesList'));
    }

    /**
     * Tampilkan detail berita berdasarkan ID (hanya yang status = view).
     */
    public function show(int $id)
    {
        $item = News::where('id', $id)
                    ->where('status', 'view')
                    ->firstOrFail();

        $recentNews = News::where('id', '!=', $id)
                          ->where('status', 'view')
                          ->latest()
                          ->take(5)
                          ->get();

        return view('user.media.news.news_detail', compact('item', 'recentNews'));
    }

    /**
     * (Opsional) Form buat berita baru — jika user bisa input.
     */
    public function create()
    {
        // Ambil daftar series untuk dropdown form
        $seriesList = [
            'Bengkalis Series',
            'Indragiri Hilir Series',
            'Indragiri Hulu Series',
            'Kampar Series',
            'Kepulauan Meranti Series',
            'Kuantan Singingi Series',
            'Pelalawan Series',
            'Rokan Hilir Series',
            'Rokan Hulu Series',
            'Siak Series',
            'Dumai Series',
            'Pekanbaru Series'
        ];

        return view('user.media.news.news_create', compact('seriesList'));
    }

    /**
     * (Opsional) Simpan berita baru ke database.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'series'     => 'required|in:Bengkalis Series,Indragiri Hilir Series,Indragiri Hulu Series,Kampar Series,Kepulauan Meranti Series,Kuantan Singingi Series,Pelalawan Series,Rokan Hilir Series,Rokan Hulu Series,Siak Series,Dumai Series,Pekanbaru Series',
            'title'      => 'required|string|max:255',
            'posted_by'  => 'required|string|max:100',
            'image'      => 'nullable|image|mimes:png,jpeg,jpg|max:1024',
            'content'    => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME))
                       . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/news'), $imageName);
            $data['image'] = 'images/news/' . $imageName;
        }

        // Default status = 'draft' jika tidak ditentukan
        $data['status'] = 'draft';

        $news = News::create($data);

        return redirect()
            ->route('user.news.show', $news->id)
            ->with('success', 'Berita berhasil dibuat.');
    }
}