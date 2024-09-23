<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institute extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'institutes';

    protected $fillable = [
        'university_name',
        'approved_by',
        'university_website',
        'verification',
        'status',
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
}
