<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $fillable = [
        'nisn', 'nama_siswa', 'class_id', 'department_id'
    ];

    public function classrooms()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function departments() 
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
