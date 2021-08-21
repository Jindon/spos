<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeSearch(Builder $query, $search)
    {
        return $query->where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('code', 'LIKE', '%' . $search . '%');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
