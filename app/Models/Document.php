<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    protected $table = 'documents';
    protected $primaryKey = 'id';

    protected $fillable = [
        'file_name', 'file_url'
    ];

    public function attached()
    {
        return $this->morphTo();
    }

    /**
     * Сохранение файла и добавление информации о нём в базу данных
     * @param $file - Сам файл из реквеста
     * @param $model - Модель, к которой файл принадлежит
     * @param string $path - Путь к файлу, который будет сохранен в системе
     * @return void
     */
    public static function saveFile($file, $model, string $path){

        $fileName = $file->getClientOriginalName();

        $file->storeAs($path, $fileName);

        $model->attaches()->create([
            'filename' => $fileName,
            'filepath' => Storage::path($file),
            'filesize' => Storage::size($file),
        ]);
    }

    /**
     * Удаление определенного файла
     * @param $model - модель, к которой файл принадлежит
     * @param $file - id файла
     * @return void
     */
    public static function deleteFile($model, $file){

        $document = $model->attaches()->find($file);

        Storage::delete($document->file_name);
        $document->delete();
    }

    /**
     * Удаление всех файлов, связанных с определенной моделью
     * @param $model - модель, к которой файл принадлежит
     * @return void
     */
    public static function deleteAllFiles($model){
        foreach ($model->attaches() as $file){
            Storage::delete($file->name);
            Document::destroy($file->id);
        }
    }
}
