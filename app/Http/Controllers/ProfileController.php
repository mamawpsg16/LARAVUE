<?php

namespace App\Http\Controllers;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request)
    {
        // dd(date('Y-m-d',strtotime($request->birth_date)));
        // dd($request->all());
        
        $user = auth()->user();
        $request->validate([
            'username' => 'required',
            'email' => 'required|email',
            'profile_picture' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->username = $request->username;
        $user->email = $request->email;
        $user->about = $request->about;
        $user->birth_date = date('Y-m-d',strtotime($request->birth_date));

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $original_name = $image->getClientOriginalName() . '.' . $image->getClientOriginalExtension();
            $filename = $image->hashName();
            $image->storeAs('profile_pictures', $filename, 'public');

            // Delete old profile picture if exists
            Storage::disk('public')->delete('profile_pictures/' . $user->hash_image_name);

            $user->image_name = $original_name;
            $user->hash_image_name = $filename;
        }

        $user->save();
        return response()->json(['message' => 'Profile updated successfully','user' => $user]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
