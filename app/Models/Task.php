<?php

namespace App\Models;

use App\Http\Controllers\TaskController;
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

    public const STATUSES_NUM = [
      'Новое' => 1,
      'В работе' => 2,
      'На проверке' => 3,
      'Готово' => 4,
    ];

    public const STATUSES = [
        1 => 'Новое',
        2 => 'В работе',
        3 => 'На проверке',
        4 => 'Готово',
    ];

    protected $fillable = [
        'name', 'description', 'deadline', 'status', 'category_id'
    ];

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

    public function mail()
    {
        return $this->morphOne(Mailed::class, 'mailable');
    }


    public static function dateFormat($date){
        $date = new DateTime($date);
        return $date->format("d.m.Y H:i");
    }

    public static function dateEqual($date, $today): bool
    {
        $date = $date->format('j');
        $today = $today->format('j');

        if (intval($date) == intval($today))
        {
            return True;
        } else {
            return False;
        }
    }

    public static function isDeadline($deadline)
    {
        $deadline = new DateTime($deadline);
        $today = new DateTime(now(new \DateTimeZone('Europe/Moscow')));

        if ($deadline > $today){
            if (Task::dateEqual($deadline, $today)){
                return 'today';
            }
            return 'yes';
        } elseif (Task::dateEqual($deadline, $today)){
            if ($deadline < $today){
                return 'no';
            }
            return 'today';
        } else {
            return 'no';
        }

    }

}
