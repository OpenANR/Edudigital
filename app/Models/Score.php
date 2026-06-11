<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $table = 'scores';
    protected $fillable = [
        'student_id', 'subject_id', 'tugas1', 'tugas2', 'asts',
        'tugas4', 'tugas5', 'asas', 'nilai_akhir', 'status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject() 
    {
        return $this->belongsTo(Subject::class);
    }

}
