<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;
use DB;
use App\Configuration;
use Spatie\ImageOptimizer\OptimizerChainFactory;

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
        $maxSize = 10; //megabytes
        $visibility = 'public';
        $type = 'local';
        $subDirectoryPath = ''; // 'Media'
        $path = '';

        $authUser = auth()->user();

        try {

            if (!isset($uploadedFile))
                abort(400, 'File not uploaded');

            $size = $uploadedFile->getClientSize(); // bytes
            
            if (!in_array($uploadedFile->guessExtension(), $allowedExtensions))
                abort(400, 'Unallowed file type error');
            
            if ($size > ($maxSize * 1024 * 1024) || $size <= 0)
                abort(400, 'File size error');

            // put the file in the desired disk, under desired location, with given visibility
            $path = Storage::disk($type)->putFile($subDirectoryPath, $uploadedFile, $visibility);      
            $uri = Storage::disk($type)->url($path);
            $uri_detail = pathinfo($uri);

            $id = DB::table('media')->insertGetId([
                'user_id' => auth()->user()->id,
                'base_path' => $uri_detail['dirname'],
                'filename' => $uri_detail['filename'],
                'name' => isset($name) ? $name : $uploadedFile->getClientOriginalName(),
                'type' => $uri_detail['extension'],
                'size' => round($size / 1024, 2), // killobytes
                'optimized' => 'N',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ]);
            
            $media = Media::where('id', $id)->first();
            return $media;
        } catch (Exception $e) {
            if (Storage::disk($type)->exists($path)) {
                Storage::disk($type)->delete($path);
            }
            Media::where('id', $id)->delete();
            abort(500, $e->getMessage());
        }
    }


    public static function destroy($id)
    {
        try {
            $type = 'local';
            $path = self::getPath($id);

            if (Storage::disk($type)->exists($path)) {
                Storage::disk($type)->delete($path);
            }

            Media::where('id', $id)->delete();           
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
        $type = 'local';
        return Storage::disk($type)->getAdapter()->getPathPrefix() . $this->filename . '.' . $this->type;
    }

    /**
     * Returns the relative path
     */
    public function relativePath()
    {
        return $this->filename . '.' . $this->type;
    }

    /**
     * Optimize the images daily
     */
    public function optimizeImageDaily()
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

    /**
     * Optimize the image
     */
    public function optimize()
    {
        $type = 'local';
        $path = self::getPath($this->id);

        if (Storage::disk($type)->exists($path)) {
            try {
                $path = $this->absolutePath();
                $optimizerChain = OptimizerChainFactory::create();
                $optimizerChain->optimize($path);

                $path = self::getPath($this->id);
                $size = Storage::disk($type)->size($path);
                $size = round($size / 1024, 2);

                $media = Media::FindOrFail($this->id);
                $media->fill(['optimized' => 'Y', 'size' => $size])->save();
                return ['status' => 'success'];
            } catch (Exception $e) {
                abort(500, $e->getMessage());
            }
        }
    }

    private static function getPath($id) 
    {
        $media = Media::where('id', $id)->first();
        return $media->filename . '.' . $media->type;
    }
}
