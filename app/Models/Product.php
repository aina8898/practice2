<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'price',
        'stock',
        'company_id',
        'comment',
        'image',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * 商品の検索処理
     */
    public static function searchProducts($filters)
    {
        $query = self::query();

        if (!empty($filters['keyword'])) {
            $query->where('product_name', 'LIKE', "%" . $filters['keyword'] . "%");
        }

        if (!empty($filters['company'])) {
            $query->where('company_id', 'LIKE', "%" . $filters['company'] . "%");
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (!empty($filters['min_stock'])) {
            $query->where('stock', '>=', $filters['min_stock']);
        }

        if (!empty($filters['max_stock'])) {
            $query->where('stock', '<=', $filters['max_stock']);
        }

        if (!empty($filters['sort'])) {
            $direction = $filters['direction'] == 'desc' ? 'desc' : 'asc';
            $query->orderBy($filters['sort'], $direction);
        }

        return $query->paginate(3);
    }

    /**
     * 商品の作成処理
     */
    public static function createProduct($data)
    {
        $product = new self($data);

        if (!empty($data['image'])) {
            $filename = $data['image']->getClientOriginalName();
            $filePath = $data['image']->storeAs('products', $filename, 'public');
            $product->image = '/storage/' . $filePath;
        }

        $product->save();
        return $product;
    }
}
