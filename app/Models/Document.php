<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';
    protected $primaryKey = 'id';

    protected $fillable = [
        'file_name', 'task_id'
    ];

    public function attached()
    {
        return $this->morphTo();
    }
}
