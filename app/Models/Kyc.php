<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_type',
        'document_type',
        'document_number',
        'pan_number',
        'gst_number',
        'id_front_path',
        'id_back_path',
        'pan_doc_path',
        'gst_doc_path',
        'status',
        'rejection_reason',
        'approved_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
