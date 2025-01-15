<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmissionInfo extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'admissions_info';
    protected $fillable = [
        'center_id',
        'university_id',
        'course_id',
        'total',
        'passout',
        'fees1_amount',
        'fees2_amount',
        'fees3_amount',
        'fees4_amount',
        'fees5_amount',
        'fees1_date',
        'fees2_date',
        'fees3_date',
        'fees4_date',
        'fees5_date',
        'fees1_trans_id',
        'fees2_trans_id',
        'fees3_trans_id',
        'fees4_trans_id',
        'fees5_trans_id',
        'fees1_status',
        'fees2_status',
        'fees3_status',
        'fees4_status',
        'fees5_status',
        'fees1_date',
        'fees2_date',
        'fees3_date',
        'fees4_date',
        'fees5_date',
        'student_name',
        'father_name',
        'mother_name',
        'address',
        'mobile',
        'email',
        'status',
        'enrollment_number',
        'roll_number',
        'dob',
        'notes',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function tablename()
    {
        return $this->table;
    }
}
