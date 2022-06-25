<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'surname', 'phone_number', 'email'];
    protected $hidden = ['pivot'];
    use HasFactory;

    public function groups()
    {
        return $this->belongsToMany(CustomerGroup::class);
    }
}
