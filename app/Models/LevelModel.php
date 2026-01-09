<?php
<<<<<<< HEAD
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
=======

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
>>>>>>> 8ff7ed2912a5ba3f923844970145ce6766b169aa

class LevelModel extends Model
{
    protected $table = 'm_level';
    protected $primaryKey = 'level_id';
<<<<<<< HEAD
    protected $fillable = ['level_kode', 'level_nama'];
}
=======

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class);
    }
}
>>>>>>> 8ff7ed2912a5ba3f923844970145ce6766b169aa
