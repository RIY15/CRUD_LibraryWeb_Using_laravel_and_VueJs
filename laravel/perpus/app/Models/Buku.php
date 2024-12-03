<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    /** @use HasFactory<\Database\Factories\BukuFactory> */
    use HasFactory;

    protected $table = 'buku'; // Nama tabel di database
    protected $primaryKey = 'isbn'; // Ganti dengan nama kolom primary key sebenarnya
    protected $keyType = 'string';
}
