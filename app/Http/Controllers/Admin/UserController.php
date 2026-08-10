<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ImageServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        // dd($request);
        $validate = $request->validate([
            'name'  => 'required',
            'nidn' => 'required|unique:users',
            'email' => 'required|unique:users',
            'image' => 'image|mimes:jpeg,png,jpg,gif|dimensions:max_width=1500,max_height:1500',
        ]);

        $image = '';
        if ($request->file('image')) {
            $data = [
                'file' => $request->file('image'),
                'path' => public_path('storage/images/user/'),
                'modul' => 'image'
            ];
            $upload = ImageServices::uploadImage($data);
            if ($upload['status'] == true) {
                $image = $upload['name'];
            } else {
                Alert::error('Failed', 'Gagal upload file photo');
                return back();
            }
        }

        try {
            $save = new User();
            $save->name = $request->name;
            $save->username = Str::slug($request->name) . rand(10, 100);
            $save->email = $request->email;
            $save->password = Hash::make('12345678');
            $save->is_active = true;
            $save->nidn = $request->nidn;
            $save->progdi = $request->progdi;
            $save->status = $request->status;

            if($request->role == 'asesor'){
                switch ($request->status) {
                    case 'internal':
                        $code = 1;
                        break;
                    case 'external':
                        $code = 2;
                        break;
                    case 'external_dif':
                        $code = 4;
                    default:
                        $code = 3;
                        break;
                }

                $save->assessor_fee = $code;
            } else {
                $save->assessor_fee = $request->assessor_fee;
            }

            if ($image) {
                $save->image = $image;
            }
            $save->save();
            $save->assignRole($request->role);

            Alert::success('Success', Lang::get('dashboard.user.create_success'));
            return back();
        } catch (Exception $error) {
            dd($error->getMessage());
        }
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, $id)
    {
        $image = '';
        if ($request->file('image')) {
            $validate = $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif|dimensions:max_width=1500,max_height:1500',
            ]);

            $data = [
                'file' => $request->file('image'),
                'path' => public_path('storage/images/user/'),
                'modul' => 'image'
            ];
            $upload = ImageServices::uploadImage($data);
            if ($upload['status'] == true) {
                $image = $upload['name'];
            } else {
                Alert::error('Failed', 'Gagal upload file photo');
                return back();
            }
        }

        try {
            $save = User::find($id);
            $save->name = $request->name;
            // $save->username = Str::slug($request->name).rand(10, 100);
            $save->email = $request->email;
            $save->is_active = true;
            $save->nidn = $request->nidn;
            $save->progdi = $request->progdi;
            $save->status = $request->status ?? 'user';

            if($request->role == 'asesor'){
                switch ($request->status) {
                    case 'internal':
                        $code = 1;
                        break;
                    case 'external':
                        $code = 2;
                        break;
                    case 'external_dif':
                        $code = 4;
                    default:
                        $code = 3;
                        break;
                }

                $save->assessor_fee = $code;
            } else {
                $save->assessor_fee = $request->assessor_fee ?? 3;
            }

            if ($image) {
                $save->image = $image;
            }
            $save->save();
            $save->syncRoles($request->role);

            Alert::success('Success', Lang::get('dashboard.user.edit_success'));
            return back();
        } catch (Exception $error) {
            dd($error->getMessage());
        }
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

    public function profile($id)
    {
        return view('admin.users.profile');
    }

    public function profileUpdate(Request $request)
    {
        // dd($request);

        $image = '';
        if ($request->file('image')) {
            $data = [
                'file' => $request->file('image'),
                'path' => public_path('storage/images/user/'),
                'modul' => 'image'
            ];
            $upload = ImageServices::uploadImage($data);
            if ($upload['status'] == true) {
                $image = $upload['name'];
            } else {
                Alert::error('Failed', 'Gagal upload file photo');
                return back();
            }
        }

        try {
            $save = User::findOrfail($request->id);
            $save->nidn = $request->nidn;
            $save->name = $request->name;
            $save->email = $request->email;
            // $save->status = $request->status;
            $save->progdi = $request->progdi;
            $save->campus_origin = $request->campus_origin;
            $save->assessor_fee = $request->assessor_fee;

            if ($image) {
                $save->image = $image;
            }

            $save->save();

            Alert::success('Berhasil', 'Profile berhasil diupdate.');
            return back();
        } catch (Exception $e) {
            Alert::error('Error', $e->getMessage());
            return back();
        }
        // return view('admin.users.profile');
    }

    public function profileUpdatePassword(Request $request)
    {
        $request->validate([
            'password_current' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::find(auth()->user()->id);

        // Check if the current password matches the one in the database
        if (Hash::check($request->password_current, $user->password)) {
            // Update the user's password with the new one
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            Alert::success('Success', 'Password changed successfully.');
            return back();
        } else {
            Alert::error('Fail', 'The current password is incorrect.');
            return back();
        }
    }
}
