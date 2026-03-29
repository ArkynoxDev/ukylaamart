<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id','name','slug','description',
        'price','stock','emoji','image','is_active'
    ];
    protected $casts = ['price'=>'decimal:2','is_active'=>'boolean'];

    public function category() { return $this->belongsTo(Category::class); }
    public function orderItems() { return $this->hasMany(OrderItem::class); }
    public function getPriceFormattedAttribute() { return 'Rp '.number_format($this->price,0,',','.'); }
    public function getIsAvailableAttribute() { return $this->is_active && $this->stock > 0; }
    public function getImageUrlAttribute() {
        if ($this->image && file_exists(public_path('uploads/products/'.$this->image)))
            return asset('uploads/products/'.$this->image);
        return null;
    }
    public function scopeActive($query) { return $query->where('is_active',true); }
    public function scopeSearch($query,$keyword) {
        return $query->where(function($q) use ($keyword) {
            $q->where('name','like',"%{$keyword}%")->orWhere('description','like',"%{$keyword}%");
        });
    }
}