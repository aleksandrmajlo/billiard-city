<?php


namespace App\Http\Controllers;

//use App\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
//use Intervention\Image\Facades\Image;


class UploadImagesController extends Controller
{
    private $photos_path;

    public function __construct()
    {
        $this->photos_path = public_path('/uploads/category');
    }

    public function store(Request $request)
    {
        $photos = $request->file('file');

        if (!is_array($photos)) {
            $photos = [$photos];
        }
        if (!is_dir($this->photos_path)) {
            mkdir($this->photos_path, 0777);
        }
        for ($i = 0; $i < count($photos); $i++) {
            $photo = $photos[$i];
            $name = sha1(date('YmdHis') . Str::random(20));
            $save_name = $name . '.' . $photo->getClientOriginalExtension();
            $photo->move($this->photos_path, $save_name);
            return Response::json([
                'suc' =>true,
                'image'=>'/uploads/category/'.$save_name
            ], 200);
        }
        return Response::json([
            'suc' => 'false',
        ], 200);

    }


    public function destroy(Request $request)
    {
        /*
        $filename = $request->name;
        $uploaded_image = Upload::where('original_name', basename($filename))->latest('id')->first();
        if (empty($uploaded_image)) {
            return Response::json(['message' => 'Sorry file does not exist'], 400);
        }
        $file_path = $this->photos_path . '/' . $filename;
        $resized_file = $this->photos_path . '/' . $uploaded_image->resized_name;
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        if (file_exists($resized_file)) {
            unlink($resized_file);
        }
        if (!empty($uploaded_image)) {
            $uploaded_image->delete();
        }
        */
        return Response::json(['message' => 'File successfully delete'], 200);
    }



}
