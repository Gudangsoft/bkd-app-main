<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use RealRashid\SweetAlert\Facades\Alert;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index', [
            'data' => Setting::first(),
            'users' => User::where('is_active', true)->role('admin')->get(),
        ]);
    }

    public function manualBook()
    {
        return view('admin.settings.manual_book', [
            'data' => Setting::first()
        ]);
    }

    public function store(Request $request)
    {
        if ($request->logo) {
            // $path = Storage::disk('public')->put('logo', $request->file);
            $this->validate($request, ['logo' => 'max:5000']);
            $logoName = time() . '.' . $request->file('logo')->extension();
            $path = Storage::putFileAs('public/logo', $request->file('logo'), $logoName);
        }

        if ($request->manual_book != null) {
            $validator = FacadesValidator::make($request->all(), [
                'manual_book' => 'required|mimes:pdf|max:5048', // PDF file, maximum size 5MB
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $pdfName = time() . '.' . $request->file('manual_book')->extension();
            $path = Storage::putFileAs('public/manual_book', $request->file('manual_book'), $pdfName);
        }

        if ($request->login_background) {
            $this->validate($request, ['login_background' => 'image|max:5000']);
            $loginBackgroundName = time() . '.' . $request->file('login_background')->extension();
            $path = Storage::putFileAs('public/login_background', $request->file('login_background'), $loginBackgroundName);
        }

        try {
            if ($request->setting_id > 0) {
                $save = Setting::find($request->setting_id);
            } else {
                $save = new Setting();
            }
            $save->title = $request->title;
            $save->email = $request->email;
            $save->phone = $request->phone;
            $save->owner_id = $request->owner_id;
            $save->address = $request->address;
            
            if ($request->manual_book != null) {
                $save->manual_book = $pdfName;
            }
            
            $save->description = $request->description;
            if ($request->logo) {
                $save->logo = $logoName;
            }
            if ($request->login_background) {
                $save->login_background = $loginBackgroundName;
            }
            $save->save();

            Alert::success('Tersimpan', 'Data berhasil ditambahkan');
            return back();
        } catch (Exception $error) {
            dd($error->getMessage());
        }
    }
}
