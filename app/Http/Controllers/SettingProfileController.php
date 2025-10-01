<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SettingProfile;

class SettingProfileController extends Controller
{
    // Tampilkan semua data
    public function index()
    {
        $data = SettingProfile::latest()->get();
        return view('setting-profile.index', compact('data'));
    }

    // Form tambah data
    public function create()
    {
        return view('setting-profile.create');
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|max:2048'
        ]);

        $data = $request->only('judul', 'deskripsi');

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('file'), $filename);
            $data['file'] = 'file/' . $filename;
        }

        SettingProfile::create($data);

        return redirect()->route('Admin.setting-profile.index')->with('success', 'Setting profile created.');
    }

    // Tampilkan detail satu item
    public function show($id)
    {
        $profile = SettingProfile::findOrFail($id);
        return view('setting-profile.show', compact('profile'));
    }

    // Form edit data
    public function edit($id)
    {
        $profile = SettingProfile::findOrFail($id);
        return view('setting-profile.edit', compact('profile'));
    }

    // Proses update data
    public function update(Request $request, $id)
    {
        $profile = SettingProfile::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|max:2048'
        ]);

        $data = $request->only('judul', 'deskripsi');

        if ($request->hasFile('file')) {
            // Hapus file lama
            if ($profile->file && file_exists(public_path($profile->file))) {
                unlink(public_path($profile->file));
            }

            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('file'), $filename);
            $data['file'] = 'file/' . $filename;
        }

        $profile->update($data);

        return redirect()->route('Admin.setting-profile.index')->with('success', 'Setting profile updated.');
    }

    // Hapus data
    public function destroy($id)
    {
        $profile = SettingProfile::findOrFail($id);

        if ($profile->file && file_exists(public_path($profile->file))) {
            unlink(public_path($profile->file));
        }

        $profile->delete();

        return redirect()->route('Admin.setting-profile.index')->with('success', 'Setting profile deleted.');
    }
}
