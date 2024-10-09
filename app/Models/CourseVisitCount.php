<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseVisitCount extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'course_visit_count';

    protected $fillable = [
        'user_id','course_id','status','visit_count'
    ];

    public function tablename()
    {
        return $this->table;
    }

}
