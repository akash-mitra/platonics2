<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;
use DB;
use App\Configuration;
use ImageOptimizer;

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

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['user_id'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['user'];

    public function getUserAttribute()
    {
        return User::where('id', $this->user_id)->first();
    }


    // Static Global Variables
    protected static $allowedExtensions = ['jpeg', 'jpg', 'png', 'bmp', 'gif'];
    protected static $maxSize = 10; //megabytes
    protected static $visibility = 'public';
    protected static $subDirectoryPath = ''; // 'Media'

    // Storage Type
    protected static $storageType = 'public';

    /**
     * Set the media storage type
     */
    private static function setStorageType()
    {
        $storage = Configuration::getConfig('storage');
        $value = $storage['storage'];

        if ($value['type'] == 's3') { 
            self::$storageType = 's3';
            \Config::set('filesystems.disks.s3.key', $value['key']);
            \Config::set('filesystems.disks.s3.secret', $value['secret']);
            \Config::set('filesystems.disks.s3.region', $value['region']);
            \Config::set('filesystems.disks.s3.bucket', $value['bucket']);
        }
        return true;
    }

    /**
     * Storage the media in storage type
     */
    public static function storeMedia($uploadedFile, $name)
    {      
        $path = '';
        $authUser = auth()->user();

        try {

            // Client-side Error Handling
            if (!isset($uploadedFile))
                abort(400, 'File not uploaded');

            if (!in_array($uploadedFile->guessExtension(), self::$allowedExtensions))
                abort(400, 'Unallowed file type error');
            
            $size = $uploadedFile->getClientSize(); // bytes
            if ($size > (self::$maxSize * 1024 * 1024) || $size <= 0)
                abort(400, 'File size error');

            
            // put the file in the desired disk, under desired location, with given visibility
            self::setStorageType();
            /*$params = [
                'visibility' => self::$visibility,
                'CacheControl' => 'max-age=5184000',
                'ContentType' => $uploadedFile->getClientMimeType()
            ];*/
            $path = Storage::disk(self::$storageType)->putFile(self::$subDirectoryPath, $uploadedFile, self::$visibility);      
            $uri = Storage::disk(self::$storageType)->url($path);
            $uri_detail = pathinfo($uri);

            $id = DB::table('media')->insertGetId([
                'user_id' => auth()->user()->id,
                'base_path' => $uri_detail['dirname'],
                'filename' => $uri_detail['basename'], // $uri_detail['filename']
                'name' => isset($name) ? $name : $uploadedFile->getClientOriginalName(),
                'type' => $uploadedFile->guessExtension(), // $uri_detail['extension'],
                'size' => round($size / 1024, 2), // killobytes
                'optimized' => 'N',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ]);
            
            $media = Media::where('id', $id)->first();
            return $media;
        } catch (Exception $e) {
            if (Storage::disk(self::$storageType)->exists($path)) {
                Storage::disk(self::$storageType)->delete($path);
            }
            Media::where('id', $id)->delete();
            abort(500, $e->getMessage());
        }
    }

    /**
     * Delete the media from storage type
     */
    public static function destroyMedia($id)
    {
        try {
            $media = Media::FindOrFail($id);
            self::setStorageType();
            $path = $media->relativePath();

            if (Storage::disk(self::$storageType)->exists($path)) {
                Storage::disk(self::$storageType)->delete($path);
            }

            $media->delete();      
            return 1;
        } catch (Exception $e) {     
            abort(500, $e->getMessage());
        }
    }


    /**
     * Returns the absolute path
     */
    public function absolutePath()
    {
        return $this->base_path . '/' . $this->filename;
    }

    /**
     * Returns the relative path
     */
    public function relativePath()
    {
        return $this->filename;
    }

    /**
     * Optimize the image
     */
    public function optimize()
    {
        self::setStorageType();
        $path = $this->relativePath();

        if (Storage::disk(self::$storageType)->exists($path)) {
            try {
                $url = Storage::disk(self::$storageType)->path($path); //Storage::disk(self::$storageType)->url($path);
                ImageOptimizer::optimize($url);

                $size = Storage::disk(self::$storageType)->size($path);
                $size = round($size / 1024, 2);
                $this->optimized = 'Y';
                $this->size = $size;
                $this->save();

                return ['status' => 'success'];
            } catch (Exception $e) {
                abort(500, $e->getMessage());
            }
        }
        else
            return ['status' => 'File does not exists in storage'];
            //abort(400, 'File does not exists in storage');
    }

    /**
     * Optimize the images daily
     */
    public static function optimizeAll()
    {
        $media_id = Media::where('optimized', 'N')->pluck('id');
        try {
            foreach ($media_id as $id) {
                $media = Media::FindOrFail($id);
                $status = $media->optimize();
            }
            return ['status' => 'success'];
        } catch (Exception $e) {
            abort(500, $e->getMessage());
        }
    }

}
