<?php

namespace App\Http\Controllers;

use App\Models\events;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class EventsController extends Controller
{
    /**
     * Menampilkan daftar master data event (Admin).
     */
    public function index()
    {
        $events = events::latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    /**
     * Menampilkan form tambah event baru.
     */
    public function create()
    {
        return view('admin.events.create');
    }

   /**
     * Menyimpan data event baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|in:Musik,Festival,Seminar',
            'ticket_price' => 'required|numeric|min:0',
            'description'  => 'required|string',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Process Upload Gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
        }

        // Simpan ke Database
        events::create([
            'title'        => $request->title,
            'category'     => $request->category,
            'ticket_price' => $request->ticket_price,
            'description'  => $request->description,
            'image'        => $imagePath,
        ]);

        return redirect()->route('events.index')->with('success', 'Data event berhasil ditambahkan!');
    }
    /**
     * Display the specified resource.
     */
    public function show(events $event)
    {
        //
    }

   /**
     * Menampilkan form edit event.
     */
    public function edit(events $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Memperbarui data event di database.
     */
    public function update(Request $request, events $event)
    {
        // Validasi input
        $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|in:Musik,Festival,Seminar',
            'ticket_price' => 'required|numeric|min:0',
            'description'  => 'required|string',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = $event->image;

        // Cek jika ada file gambar baru yang diunggah
        if ($request->hasFile('image')) {
            // Hapus gambar lama dari storage jika ada
            if ($event->image && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
            }
            // Simpan gambar baru
            $imagePath = $request->file('image')->store('events', 'public');
        }

        // Update data
        $event->update([
            'title'        => $request->title,
            'category'     => $request->category,
            'ticket_price' => $request->ticket_price,
            'description'  => $request->description,
            'image'        => $imagePath,
        ]);

        return redirect()->route('events.index')->with('success', 'Data event berhasil diperbarui!');
    }

    /**
     * Menghapus data event dari database.
     */
    public function destroy(events $event)
    {
        // Hapus file gambar dari storage
        if ($event->image && Storage::disk('public')->exists($event->image)) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Data event berhasil dihapus!');
    }
}