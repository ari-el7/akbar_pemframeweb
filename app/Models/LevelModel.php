<?php
<<<<<<< Updated upstream
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
=======

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
>>>>>>> Stashed changes

class LevelModel extends Model
{
    protected $table = 'm_level';
    protected $primaryKey = 'level_id';
<<<<<<< Updated upstream
    protected $fillable = ['level_kode', 'level_nama'];
}
=======

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class);
    }
}
>>>>>>> Stashed changes
