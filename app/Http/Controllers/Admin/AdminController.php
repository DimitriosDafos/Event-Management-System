<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'userCount'   => User::count(),
            'partyCount'  => Party::count(),
            'nextParty'   => Party::upcoming()->first(),
        ]);
    }

    public function users()
    {
        $users = User::orderBy('name')->get();
        return view('admin.users', compact('users'));
    }

    public function createUser() { return view('admin.user-form', ['user' => null]); }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users|alpha_dash',
            'password' => 'required|string|min:6',
            'roles'    => 'required|array|min:1',
            'roles.*'  => 'in:admin,marketing,member,dj',
        ]);
        User::create([
            'name'     => $data['name'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'role'     => $request->input('roles', ['member']),
        ]);
        return redirect()->route('admin.users')->with('success', 'Benutzer erstellt.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user-form', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username,'.$id,
            'roles'    => 'required|array|min:1',
            'roles.*'  => 'in:admin,marketing,member,dj',
            'active'   => 'boolean',
            'password' => 'nullable|string|min:6',
        ]);
        $update = [
            'name'     => $data['name'],
            'username' => $data['username'],
            'role'     => $request->input('roles', ['member']),
            'active'   => $request->boolean('active'),
        ];
        if (!empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }
        $user->update($update);
        return redirect()->route('admin.users')->with('success', 'Benutzer aktualisiert.');
    }

    public function deleteUser($id)
    {
        if ($id == auth()->id()) return back()->withErrors(['error' => 'Du kannst dich nicht selbst löschen.']);
        User::findOrFail($id)->delete();
        return back()->with('success', 'Benutzer gelöscht.');
    }
}
