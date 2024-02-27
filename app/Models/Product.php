<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'desc',
        'code',
        'discontinued',
    ];

    /**
     * Get product by unique product code
     */
    public function findByCode($code): Model|\Illuminate\Database\Eloquent\Builder|null
    {
        return $this->query()->where('code', $code)->first();
    }

    public function vault_params() {
        return $this->hasOne(ProductVaultParams::class, 'product_id', 'id');
    }

    /**
     * Store product in DB with vault params (stock, cost)
     *
     * @param $data
     * @return mixed
     */
    public static function storeProduct($data) {
        $new_product = self::query()->create($data);
        $new_product->save();

        $new_product_vault = ProductVaultParams::query()->create([
            'stock' => $data['stock'],
            'cost' => $data['cost'],
            'product_id' => $new_product->id,
        ]);
        $new_product_vault->save();
    }
}
