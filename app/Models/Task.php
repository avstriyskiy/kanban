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

    public function getCategory(){
        return $this->hasOne(Category::class, 'tasks_category_id_foreign', 'category_id');
    }
}
