<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('name');
            $table->longText('description')->nullable();
            $table->string('category');
            $table->decimal('price', 10, 2);
            $table->enum('condition', ['Neuf', 'Utilisé']);
            $table->boolean('bio')->default(false);
            $table->integer('quantity')->default(0);

            $table->foreignId('seller_id')
                  ->constrained('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['seller_id']);
            $table->dropColumn([
                'name',
                'description',
                'category',
                'price',
                'condition',
                'bio',
                'quantity',
                'seller_id'
            ]);
        });
    }
};