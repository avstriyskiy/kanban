<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class Task extends Model
{
    protected $table = 'tasks';
    protected $primaryKey = 'id';
    use HasFactory;
    /**@property $id
     * @property $name
     * @property $description
     * @property $deadline
     * @property $status
     * @property $category_id
     */

    protected $fillable = [
        'name', 'description', 'deadline', 'status', 'category_id', 'has_files'
    ];

    public function categories(){

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function attaches()
    {
        return $this->morphMany(Document::class, 'attached');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
