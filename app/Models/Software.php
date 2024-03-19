<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\So;

class Software extends Model
{
    use HasFactory;

    public $table = 'software';
    protected $fillable = ['sku', 'name', 'price','id_so', 'serial'];

    protected static function boot()

    {
        parent::boot();
    
        static::creating(function ($software) {
            $software->serial = bin2hex(random_bytes(50));
        });
    }

    public function so()
    {
        return $this->belongsTo(SO::class, 'id_so');
    }
}
