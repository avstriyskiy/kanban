<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    use HasFactory;

    public static function getAllCategories(){
        $categories = [];
        $all_data = Category::all();

        foreach ($all_data as $category){
            $categories[] = $category->name;
        }

        return $categories;
    }
}
