<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idcode', 'category_id', 'description', 'brand', 'serialnumber', 'supplier', 'purchasecost', 'purchasedate', 'license', 'name', 'email', 'dept', 'status', 'notes', 'history', 'checkdate', 'checkedby',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
