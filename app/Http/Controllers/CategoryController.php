<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('nama', 'ASC')->get();
        $eventCounts = \App\Models\Event::selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori');

        return view('categories.index', compact('categories', 'eventCounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50|unique:categories',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);

        $validated['icon'] = !empty($validated['icon']) ? trim($validated['icon']) : 'fa-tag';
        $validated['color'] = !empty($validated['color']) ? trim($validated['color']) : '#4F46E5';

        Category::create($validated);
        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $oldName = $category->nama;

        $validated = $request->validate([
            'nama' => 'required|string|max:50|unique:categories,nama,' . $id,
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);

        $validated['icon'] = !empty($validated['icon']) ? trim($validated['icon']) : 'fa-tag';
        $validated['color'] = !empty($validated['color']) ? trim($validated['color']) : '#4F46E5';

        $category->update($validated);

        if ($oldName !== $validated['nama']) {
            \App\Models\Event::where('kategori', $oldName)->update(['kategori' => $validated['nama']]);
        }

        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Cek apakah kategori digunakan di event
        $used = \App\Models\Event::where('kategori', $category->nama)->exists();

        if ($used) {
            return redirect()->route('admin.categories')->with('error', 'Kategori tidak dapat dihapus karena masih digunakan!');
        }

        $category->delete();
        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil dihapus!');
    }
}