<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $table = 'classrooms';
    protected $fillable = [
        'tingkat', 'nama_kelas', 'department_id', 'user_id'
    ];

    public function departments() 
    {
        return $this->belongsTo(Department::class);
    }

    public function users() 
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function teachingSchedules()
    {
        return $this->hasMany(TeachingSchedule::class);
    }
    
}
