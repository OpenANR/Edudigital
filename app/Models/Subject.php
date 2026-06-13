<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';
    protected $fillable = [
       'kode_mapel', 'nama_mapel'
    ];

    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
