<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function profile(Request $request)
    {
        $storage = Storage::allFiles();

        $user = auth()->user();
        // return redirect()->route('profile')->with('success', 'Profile updated.');



        return view('backend.user.profile',compact('user'));
    }

    
    public function updateProfilePhoto(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            abort(404);
        }

        $this->validate($request, [
            'photo' => 'mimes:jpeg,jpg,png|max:2000|dimensions:min_width=50,min_height=50',
        ]);

        if ($request->hasFile('photo')) {
            // Handle the file upload and update the photo field in the database
            // $imgStorePath = "public/users";
            // $storagepath = $request->file('photo')->store($imgStorePath);

            $file = $request->file('photo');
            $fileName = time() . '_' . md5($file->getClientOriginalName()) . '.' . $file->extension();
            $filePath = 'uploads/' . $fileName;
            Storage::put($filePath, file_get_contents($file));
            // $urlPath = Storage::url($filePath);

            $user_photo = $filePath;


            // Delete the old photo if exists
            $oldFile = $user->photo;
            if ($oldFile) {
                Storage::delete($oldFile);
            }

            // Update the student's photo field
            $update = $user->update(['photo' => $user_photo]);
            if ($update) {
                return redirect()->back()->with('success', 'Profile Photo updated!');
            } else {
                return redirect()->back()->with('error', 'Failed to update profile photo in the database.')->withInput();
            }
        }
        return redirect()->back()->with('success', 'Profile Photo updated!');
    }
}
