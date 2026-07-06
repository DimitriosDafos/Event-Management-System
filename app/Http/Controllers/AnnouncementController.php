<?php
namespace App\Http\Controllers;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderBy('sort_order')->orderByDesc('created_at')->get();
        return view('announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:200',
            'body'       => 'required|string|max:5000',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        Announcement::create([
            ...$data,
            'sort_order' => $data['sort_order'] ?? 0,
            'active'     => true,
            'created_by' => auth()->id(),
        ]);
        return back()->with('success', 'Ankündigung gespeichert.');
    }

    public function update(Request $request, int $id)
    {
        $announcement = Announcement::findOrFail($id);
        $data = $request->validate([
            'title'      => 'required|string|max:200',
            'body'       => 'required|string|max:5000',
            'active'     => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $announcement->update([
            ...$data,
            'active'     => $request->boolean('active'),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);
        return back()->with('success', 'Ankündigung aktualisiert.');
    }

    public function destroy(int $id)
    {
        Announcement::findOrFail($id)->delete();
        return back()->with('success', 'Ankündigung gelöscht.');
    }
}
