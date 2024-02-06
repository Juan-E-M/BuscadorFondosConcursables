<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crl extends Model
{
    use HasFactory;
    protected $table = 'crl';
    protected $fillable = ['name', 'description'];
}
