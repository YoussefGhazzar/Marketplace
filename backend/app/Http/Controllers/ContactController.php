<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomComplet' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'sujet' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contact = Contact::create([
            'nom_complet' => $validated['nomComplet'],
            'email' => $validated['email'],
            'sujet' => $validated['sujet'],
            'message' => $validated['message'],
        ]);

        return response()->json([
            'message' => 'Message envoyé avec succès ! Nous vous répondrons bientôt.',
            'contact' => $contact
        ], 201);
    }
}
