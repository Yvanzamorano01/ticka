<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function showForm()
    {
        return view('contact');
    }

    public function submitForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'message' => 'required',
        ]);

        // (Optionnel) Envoyer un email à l'administrateur
        Mail::raw($request->message, function ($message) use ($request) {
            $message->to('admin@example.com') // Remplacez par l'email de l'administrateur
                    ->subject('New Contact Us Message')
                    ->from($request->email);
        });

        return redirect()->route('contact.form')->with('success', 'Your message has been sent successfully!');
    }
}
