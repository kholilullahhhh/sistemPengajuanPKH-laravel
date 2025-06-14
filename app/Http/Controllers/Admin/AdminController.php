<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnggotaRumahTangga;
use App\Models\KepalaRumah;
use App\Models\PenerimaPKH;
use App\Models\KategoriKeluhan;
use App\Models\Keluhan;
use App\Models\Pelanggan;
use App\Models\Tanggapan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    private $menu;
    public function __construct($menu = 'dashboard')
    {
        $this->menu = $menu;
    }

    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $menu = $this->menu;
        $kategori = keluhan::with('kategori')->get();

        return view('pages.admin.index', compact('menu', 'kategori'));
    }

    public function statistik()
    {
        // Menghitung jumlah penerima PKH berdasarkan status
        $menu = 'dashboard';

        // Menyiapkan data untuk chart

        return view('pages.user.statistik', compact('menu', 'chartData'));
    }


    public function rekap()
    {
        $menu = 'rekap';
        return view('pages.admin.rekap.index', compact('menu'));
    }

    public function cetak_rekap()
    {
        // Ambil semua data penerima PKH yang telah diverifikasi (status 'S')
        // $data = PenerimaPKH::with(['asetGet', 'kepalaRumahGet', 'anggotaRumahTanggaGet', 'ketAsetGet', 'ketPerumahanGet'])
        //     ->where('status', 'S')
        //     ->get();


        // Load the view with the data
        $pdf = Pdf::loadView('pages.admin.rekap.cetak', compact('data'))
            ->setPaper('a3', 'landscape');
        ;

        // Download the PDF
        return $pdf->download('data-rekapan-PKH.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function profile($id)
    {
        $data = User::find($id);
        return view('pages.admin.profile.index', ['menu' => 'profile', 'data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function profile_update(Request $request)
    {
        $r = $request->all();
        // $update_nik = Pegawai::where('nama_lengkap', $r['name'])->first();
        // $update->nik();


        // dd( $r['id']);
        $user = User::find($r['id']);
        if ($r['password'] != null) {
            $r['password'] = bcrypt($r['password']);
            // dump('ubah password');
        } else {
            unset($r['password']);
        }
        // dd(true);

        $user->update($r);

        return redirect()->route('dashboard')->with('message', 'update profile');
    }
}
