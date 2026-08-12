<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'total' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|integer|exists:products,id',
            'items.*.nom' => 'required|string|max:255',
            'items.*.vendeur' => 'required|string|max:255',
            'items.*.prix' => 'required|string|max:255',
            'items.*.tag' => 'required|string|max:255',
            'items.*.image' => 'nullable|string|max:1000',
            'items.*.type' => 'required|string|in:acheter,echanger',
        ]);

        $order = DB::transaction(function () use ($validated) {
            $order = Order::create([
                'total' => $validated['total'],
                'status' => 'confirmed',
            ]);

            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'] ?? null,
                    'nom' => $item['nom'],
                    'vendeur' => $item['vendeur'],
                    'prix' => $item['prix'],
                    'tag' => $item['tag'],
                    'image' => $item['image'] ?? null,
                    'type' => $item['type'],
                ]);
            }

            return $order->load('items');
        });

        return response()->json([
            'message' => 'Commande confirmée avec succès !',
            'order' => $order
        ], 201);
    }
}
