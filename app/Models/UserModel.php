<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
<<<<<<< HEAD
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class UserModel extends Authenticatable
=======

class UserModel extends Model
>>>>>>> 8ff7ed2912a5ba3f923844970145ce6766b169aa
{
    use HasFactory;

    protected $table = 'm_user';
    protected $primaryKey = 'user_id';

<<<<<<< HEAD
    // protected $fillable = ['level_id', 'username', 'nama', 'password'];
    protected $fillable = [
        'username',
        'nama',
        'password',
        'level_id',
        'avatar',
        'created_at',
        'updated_at'
    ];

    protected $hidden = ['password']; // Jangan tampilkan password saat select

    protected $casts = ['password' => 'hashed']; // Casting password agar otomatis di-hash
=======
    protected $fillable = ['level_id', 'username', 'nama', 'password'];
    // protected $fillable = ['level_id', 'username', 'nama'];
>>>>>>> 8ff7ed2912a5ba3f923844970145ce6766b169aa

    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }
<<<<<<< HEAD

    // Mendapatkan nama role
    public function getRoleName(): string
    {
        return $this->level->level_nama;
    }

    // Cek apakah user memiliki role tertentu
    public function hasRole($role): bool
    {
        return $this->level->level_kode == $role;
    }

    // Mendapatkan kode role
    public function getRole()
    {
        return $this->level->level_kode;
    }

    // Accessor untuk mendapatkan URL avatar otomatis
    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn ($avatar) => $avatar ? asset('storage/photos/' . $avatar) : asset('adminlte/dist/img/user2-160x160.jpg'),
        );
    }

    
}
=======
}
>>>>>>> 8ff7ed2912a5ba3f923844970145ce6766b169aa
