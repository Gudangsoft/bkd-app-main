<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Services\ImageServices;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.ads.index');
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
        $request->validate([
            'title' => 'required',
            'type' => 'required|in:home_slider,home_popup',
            'url' => 'nullable|url',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $imageName = null;
            if ($request->file('image')) {
                $destinationPath = public_path('storage/images/ads/');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $upload = ImageServices::uploadImage([
                    'file' => $request->file('image'),
                    'path' => $destinationPath,
                    'modul' => 'image',
                ]);
                $imageName = $upload['name'];
            }

            Ad::create([
                'title' => $request->title,
                'url' => $request->url,
                'description' => $request->description,
                'image' => $imageName,
                'type' => $request->type,
                'status' => 1,
            ]);

            Alert::success('Berhasil', 'Ads baru berhasil ditambahkan');
            return back();
        } catch (Exception $e) {
            Alert::error('Invalid', $e->getMessage());
            return back();
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
        $request->validate([
            'title' => 'required',
            'type' => 'required|in:home_slider,home_popup',
            'url' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $ad = Ad::findOrFail($id);

            $data = [
                'title' => $request->title,
                'url' => $request->url,
                'description' => $request->description,
                'type' => $request->type,
            ];

            if ($request->file('image')) {
                $destinationPath = public_path('storage/images/ads/');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $upload = ImageServices::uploadImage([
                    'file' => $request->file('image'),
                    'path' => $destinationPath,
                    'modul' => 'image',
                ]);
                $data['image'] = $upload['name'];
            }

            $ad->update($data);

            Alert::success('Berhasil', 'Ads berhasil diupdate');
            return back();
        } catch (Exception $e) {
            Alert::error('Invalid', $e->getMessage());
            return back();
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
}
