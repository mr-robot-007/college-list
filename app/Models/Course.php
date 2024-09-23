<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'courses';

    protected $fillable = [
        'title',
        'type',
        'duration',
        'visit',
        'passout_1',    
        'passout_2',
        'passout_3',
        'passout_4',
        'passout_5',
        'passout_6',
        'passout_7',
        'passout_8',
        'passout_9',
        'passout_10',
        'fees_1',
        'fees_2',
        'fees_3',
        'fees_4',
        'fees_5',   
        'fees_6',
        'fees_7',
        'fees_8',
        'fees_9',
        'fees_10',
        'status',
        'institute_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_by_system'
        // Add other fillable attributes here if needed
    ];

    public function tablename()
    {
        return $this->table;
    }

    public function instructor()
    {
        return $this->belongsTo(User::class,'instructor_id','id');
    }
}
