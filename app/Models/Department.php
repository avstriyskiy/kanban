<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category(){
        return $this->hasOne(Category::class);
    }

    public static function getAllDepartments(){
        $departments = [];
        $all_data = Department::all();

        foreach ($all_data as $department){
            $departments[] = $department->name;
        }

        return $departments;
    }

    public static function getDaughterDepartments(){
        $departments = [];
        $all_data = Department::where('parent_id', '>' , 0)->get();

        foreach ($all_data as $department){
            $departments[] = $department->name;
        }

        return $departments;
    }

    public static function getParentDepartments(){
        $departments = [];
        $all_data = Department::where('parent_id', '=' , 0)->get();

        foreach ($all_data as $department){
            $departments[] = $department->name;
        }

        return $departments;
    }
}
