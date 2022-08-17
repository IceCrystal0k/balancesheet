<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyBalanceSheet extends Model
{
    use HasFactory;

    public function typeInfo()
    {
        return $this->hasOne(BalanceType::class, 'id', 'type_id');
    }

    public function productInfo()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function targetInfo()
    {
        return $this->hasOne(Target::class, 'id', 'target_id');
    }
}
