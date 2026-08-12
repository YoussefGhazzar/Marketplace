<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');
        $query = Product::query();

        if ($type === 'soins') {
            $query->whereNotIn('categorie', ['jellaba', 'kaftan', 'accessoires', 'Jellaba', 'Kaftan', 'Accessoires']);
        } elseif ($type === 'artisanat') {
            $query->whereIn('categorie', ['jellaba', 'kaftan', 'accessoires', 'Jellaba', 'Kaftan', 'Accessoires']);
        }

        $products = $query->latest()->get();

        // Map the product models to have the full photo URL if available
        $products = $products->map(function ($product) {
            if ($product->photo) {
                $product->photo_url = asset('storage/' . $product->photo);
            } else {
                $product->photo_url = null;
            }
            return $product;
        });

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomVendeuse' => 'required|string|max:255',
            'typeVendeur' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'categorie' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'description' => 'required|string',
            'typeOffre' => 'required|string|max:255',
            'photo' => 'nullable|image|max:5120', // Max 5MB
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('products', 'public');
        }

        $product = Product::create([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'categorie' => $validated['categorie'],
            'prix' => $validated['prix'],
            'nom_vendeuse' => $validated['nomVendeuse'],
            'type_vendeur' => $validated['typeVendeur'],
            'type_offre' => $validated['typeOffre'],
            'photo' => $photoPath,
        ]);

        if ($product->photo) {
            $product->photo_url = asset('storage/' . $product->photo);
        }

        return response()->json([
            'message' => 'Produit publié avec succès !',
            'product' => $product
        ], 201);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        if ($product->photo) {
            $product->photo_url = asset('storage/' . $product->photo);
        }
        return response()->json($product);
    }
}
