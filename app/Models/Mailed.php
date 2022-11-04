<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\User;

class Mailed extends Model
{
    protected $table = 'mailed';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function mailable(){
        return $this->morphTo();
    }
}
