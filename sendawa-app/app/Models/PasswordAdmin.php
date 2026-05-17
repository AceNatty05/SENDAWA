<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordAdmin extends Model
{
    protected $table = 'password_admin';

    protected $fillable = ['password'];

    /**
     * Kolom yang di-hidden agar tidak ter-expose saat serialisasi.
     */
    protected $hidden = ['password'];
}
