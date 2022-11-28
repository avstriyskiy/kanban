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
        'filename', 'filesize', 'filepath'
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
        $fileSize = Storage::size($fileName);
        $filePath = Storage::path($fileName);

        $model->attaches()->create([
            'filename' => $fileName,
            'filepath' => $filePath,
            'filesize' => $fileSize,
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
        Storage::delete($document->filename);
        $document->delete();
    }

    /**
     * Удаление всех файлов, связанных с определенной моделью
     * @param $model - модель, к которой файл принадлежит
     * @return void
     */
    public static function deleteAllFiles($model){
        foreach ($model->attaches as $file){
            Storage::delete($file->filename);
            Document::destroy($file->id);
        }
    }
}
