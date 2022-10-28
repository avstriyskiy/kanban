<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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



    public function getCategory(){
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
