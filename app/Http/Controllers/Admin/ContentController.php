<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use Illuminate\Http\Request;

class ContentController extends Controller {
    public function index() {
        // Ambil semua konten dan ubah jadi array key => value
        $contents = SiteContent::pluck('value', 'key');
        return view('admin.content.settings', compact('contents'));
    }

    public function update(Request $request) {
    foreach ($request->settings as $key => $data) {
        $content = SiteContent::firstOrNew(['key' => $key]);
        $content->title = $data['title'] ?? null;
        $content->value = $data['value'] ?? null;

        if ($request->hasFile("settings.$key.image")) {
            $path = $request->file("settings.$key.image")->store('contents', 'public');
            $content->image = $path;
        }

        $content->save();
    }
    return back()->with('success', 'Konten Berhasil Diperbarui!');
}
}