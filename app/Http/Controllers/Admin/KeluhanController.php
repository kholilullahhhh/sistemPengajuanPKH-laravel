<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriKeluhan;
use App\Models\Keluhan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class KeluhanController extends Controller
{
    private $menu;
    public function __construct()
    {
        $this->menu = 'keluhan';
    }
    public function index()
    {

        $menu = $this->menu;
        $data = Keluhan::all();
        $kategori = KategoriKeluhan::all();
        $pelanggan = Pelanggan::all();

        return view('pages.admin.keluhan.index', compact('menu', 'data', 'kategori', 'pelanggan'));
    }

    public function store(Request $request)
    {

        $menu = $this->menu;
        $data = $request->all();
        $file = $request->file('foto_bukti_keluhan');

        // dd($file->getSize() / 1024);
        // if ($file->getSize() / 1024 >= 512) {
        //     return redirect()->route('agenda.create')->with('message', 'size gambar');
        // }

        $foto = $request->file('foto_bukti_keluhan');
        $ext = $foto->getClientOriginalExtension();
        // $r['pas_foto'] = $request->file('pas_foto');

        $nameFoto = date('Y-m-d_H-i-s_') . "." . $ext;
        $destinationPath = public_path('upload/bukti');

        $foto->move($destinationPath, $nameFoto);

        $fileUrl = asset('upload/bukti/' . $nameFoto);
        // dd($destinationPath);
        $data['foto_bukti_keluhan'] = $nameFoto;
        // dd($data['foto_bukti_keluhan']);

        Keluhan::create($data);


        // return redirect()->route('keluhan.index');
        return response()->json([
            'data' => $data,
            'success' => true
        ]);
    }

    public function edit(Request $request)
    {

        $menu = $this->menu;
        $data = Keluhan::find($request->id);

        return response()->json([
            'data' => $data
        ]);
    }

    public function update(Request $request)
    {

        $menu = $this->menu;
        $req = $request->all();
        // dd($request->all());
        $data = Keluhan::find($request->id);
        $data->update($req);


        return response()->json([
            'data' => $data,
            'success' => true
        ]);
    }

    public function delete($id)
    {
        $user = Keluhan::findOrFail($id);
        $user->delete();
        return response()->json(['status' => 'success', 'message' => 'keluhan delete']);
    }
}
