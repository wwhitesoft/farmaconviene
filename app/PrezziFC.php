<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrezziFC extends Model
{
    use HasFactory;
    protected $table = 'prezzi_fornitori';
    protected $primaryKey = 'id';
}
