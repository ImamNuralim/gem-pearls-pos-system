<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UploadUser;
use Illuminate\Http\Request;

class UploadUserController extends Controller
{
    public function index()
    {
        $uploadUsers = UploadUser::latest()->get();
        return view('admin.upload-users.index', compact('uploadUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:upload_users,email',
            'password' => 'required|min:6',
        ]);

        UploadUser::create($request->only('name', 'email', 'password'));
        return back()->with('success', 'Akun upload berhasil dibuat!');
    }

    public function update(Request $request, UploadUser $uploadUser)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:upload_users,email,' . $uploadUser->id,
        ]);

        $data = ['name' => $request->name, 'email' => $request->email];
        if ($request->password) {
            $data['password'] = $request->password;
        }

        $uploadUser->update($data);
        return back()->with('success', 'Akun upload berhasil diupdate!');
    }

    public function destroy(UploadUser $uploadUser)
    {
        $uploadUser->delete();
        return back()->with('success', 'Akun upload berhasil dihapus!');
    }
}
