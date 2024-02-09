<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\User;
use App\Models\Level;
use App\Models\Masakan;
use App\Models\Jmasakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $loggedInUser = auth()->user();

        // Inisialisasi variabel untuk menyimpan data user
        $users = [];

        // Menampilkan data user berdasarkan kondisi id_level
        if ($loggedInUser->id_level == 3) {
            // Jika id_level = 3, tampilkan semua data user
            $users = User::with('level')->get();
        } elseif ($loggedInUser->id_level == 2) {
            // Jika id_level = 2, tampilkan data user dengan id_level = 1 saja
            $users = User::with('level')->where('id_level', 1)->get();
        }

        return view('admin.home', compact('users'));
    }

    public function storeUsers(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'level_id' => 'required|exists:levels,id',
        ]);


        $user = new User();
        $user->nama_user = $request->name;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->id_level = $request->level_id;

        $user->save();

        return redirect()->route('home')->with('success', 'Users added successfully.');
    }

    public function deleteUsers(Request $request)
    {
        $userIds = explode(',', $request->input('user_ids'));
        User::whereIn('id', $userIds)->delete();

        return redirect()->back()->with('success', 'Users deleted successfully');
    }

    public function editUsers($id)
    {
        $user = User::findOrFail($id);
        $levels = Level::all();

        return view('admin.edituser', compact('user', 'levels'));
    }

    public function updateUsers(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $request->user_id,
            'password' => 'nullable|min:8|string|confirmed',
            'level_id' => 'required|exists:levels,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->nama_user = $request->name;
        $user->username = $request->username;
        $user->id_level = $request->level_id;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('home')->with('success', 'User updated successfully');
    }

    public function menu()
    {
        $masakans = Masakan::with('jmasakan')->get();

        return view('admin.menu', compact('masakans'));
    }

    public function createMenu()
    {
        $jmasakans = Jmasakan::all();
        return view('admin.tambahmenu', compact('jmasakans'));
    }

    public function storeMenu(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jmasakan' => 'required|exists:jmasakans,id',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'harga' => 'required',
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {
            $imageName = $request->image->store('menu_images', 'public');
        }

        $masakan = new Masakan();
        $masakan->nama_masakan = $request->nama;
        $masakan->id_jmasakan = $request->jmasakan;
        $masakan->harga = $request->harga;
        $masakan->image = $imageName;

        $masakan->save();

        return redirect()->route('menu')->with('success', 'Menu added successfully.');
    }

    public function deleteMenu(Request $request)
    {
        $menuIds = explode(',', $request->input('menu_ids'));
        $menuToDelete = Masakan::whereIn('id', $menuIds)->get();

        foreach ($menuToDelete as $menu) {
            // Hapus file gambar terkait
            $imagePath = 'public/menu_images/' . basename($menu->image);
            if (Storage::exists($imagePath)) {
                Storage::delete($imagePath);
            }
        }

        Masakan::whereIn('id', $menuIds)->delete();

        return redirect()->back()->with('success', 'Menu deleted successfully');
    }


    public function editMenu($id)
    {
        $masakan = Masakan::findOrFail($id);
        $jmasakans = Jmasakan::all();

        return view('admin.editmenu', compact('masakan', 'jmasakans'));
    }

    public function updateMenu(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jmasakan' => 'required|exists:jmasakans,id',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'harga' => 'required',
        ]);

        $masakan = Masakan::findOrFail($request->menu_id);
        $masakan->nama_masakan = $request->nama;
        $masakan->id_jmasakan = $request->jmasakan;
        $masakan->harga = $request->harga;

        if ($request->hasFile('image')) {
            Storage::delete('public/menu_images/' . basename($masakan->image));
            $imageName = $request->file('image')->store('menu_images', 'public');
            $masakan->image = $imageName;
        }

        $masakan->save();

        return redirect()->route('menu')->with('success', 'Menu updated successfully');
    }

    public function updateMenuStatus(Request $request)
    {
        $menuId = $request->input('menu_id');
        $newStatus = $request->input('new_status');

        $menu = Masakan::find($menuId);
        if ($menu) {
            $menu->status_masakan = $newStatus;
            $menu->save();
        }

        return response()->json(['message' => 'Status updated successfully']);
    }

    public function meja()
    {
        $mejas = Meja::all();

        return view('admin.meja', compact('mejas'));
    }

    public function createMeja()
    {
        return view('admin.tambahmeja');
    }

    public function storeMeja(Request $request)
    {
        $request->validate([
            'no_meja' => 'required|integer|unique:nomejas',
        ]);

        $meja = new Meja();
        $meja->no_meja = $request->no_meja;

        $meja->save();

        return redirect()->route('meja')->with('success', 'Data meja added successfully.');
    }

    public function deleteMeja(Request $request)
    {
        $mejaIds = explode(',', $request->input('meja_ids'));
        Meja::whereIn('no_meja', $mejaIds)->delete();

        return redirect()->back()->with('success', 'Data meja deleted successfully');
    }
}
