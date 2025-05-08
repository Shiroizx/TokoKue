<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'customer';

    /**
     * Atribut yang dapat diisi.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'google_id',
        'google_token',
        'alamat',
        'pos',
        'phone',
        'image',
    ];

    /**
     * Mendapatkan user yang terkait dengan customer ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}