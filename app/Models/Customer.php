<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function scopeSearch(Builder $query, $search)
    {
        return $query->where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('gstin', 'LIKE', '%' . $search . '%');
    }
}
