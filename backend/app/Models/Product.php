<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'nom',
        'description',
        'categorie',
        'prix',
        'nom_vendeuse',
        'type_vendeur',
        'type_offre',
        'photo',
    ];

    protected $casts = [
        'prix' => 'decimal:2',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }
}