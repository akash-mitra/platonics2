<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;
use DB;
use Auth;
use App\Configuration;

class Media extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'base_path', 'filename', 'name',
        'type', 'size', 'optimized',
    ];

    public static function store($uploadedFile, $name)
    {
        $allowedExtensions = ['jpeg', 'jpg', 'png', 'bmp', 'gif'];
        $maxSize = 1;
        $visibility = 'public';
        $type = 'local';
        $subDirectoryPath = '';
        $path = '';

        $authUser = auth()->user();
        //$storageConfig = Configuration::getConfig('storage');

        try {
            // put the file in the desired disk, under desired location, with given visibility
            $path = Storage::disk($type)->putFile($subDirectoryPath, $uploadedFile, $visibility);
            $size = $uploadedFile->getClientSize();
            $uri = Storage::disk($type)->url($path);
            $media_id = DB::table('media')->insertGetId([
                'user_id' => Auth::user()->id,
                'base_path' => $uri,
                'filename' => basename($uploadedFile->getClientOriginalName(), '.' . $uploadedFile->getClientOriginalExtension()),
                'name' => $name,
                'type' => $uploadedFile->getClientOriginalExtension(),
                'size' => round($size / 1024, 2), // killobytes
                'optimized' => 'N',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ]);
            
            return $media_id;
        } catch (Exception $e) {
            if (Storage::disk($type)->exists($path)) {
                Storage::disk($type)->delete($path);
            }
            Media::where('id', $media_id)->delete();
            abort(500, $e->getMessage());
        }
    }


    
}
