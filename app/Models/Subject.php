<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';
    protected $fillable = [
        'nama_mapel', 'kelompok_mapel', 'department_id'
    ];

    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
