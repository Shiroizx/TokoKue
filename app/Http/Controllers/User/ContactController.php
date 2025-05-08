<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller  
{  
    public function index()  
    {  
        return view('contact');  
    }  

    public function submit(Request $request)  
    {  
        $validatedData = $request->validate([  
            'name' => 'required|max:255',  
            'email' => 'required|email',  
            'message' => 'required|min:10'  
        ]);  

        // Proses pengiriman pesan (misalnya simpan ke database atau kirim email)  
        // Contoh sederhana:  
        return redirect()->back()->with('success', 'Pesan berhasil dikirim!');  
    }  
}  