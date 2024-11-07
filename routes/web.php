<?php

use App\Models\KotakSaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('kotak_saran');
});

Route::post('/kirim_saran', function (Request $request) {
    $validator = Validator::make(request()->all(), [
        'g-recaptcha-response' => 'recaptcha',
    ])->validate();

    if ($request->lampiran != null) {
        $imageName = time() . '.' . $request->lampiran->extension();
        $request->lampiran->move(storage_path('app/public/lampiran-kotak-saran'), $imageName);
    }

    try {
        KotakSaran::create([
            'nama' => $request->nama,
            'nomor' => $request->nomor,
            'email' => $request->email,
            'pesan' => $request->pesan,
            'lampiran' => '/lampiran-kotak-saran/' . $imageName,
        ]);

        return redirect()->back()->with('success', 'Saran Anda berhasil dikirim!');
    } catch (\Throwable $th) {

        // return redirect()->back()->with('error', $th->getMessage());
        return redirect()->back()->with('error', 'Masih ada data yang belum diisi!');
    }
})->name('kirim_saran');
