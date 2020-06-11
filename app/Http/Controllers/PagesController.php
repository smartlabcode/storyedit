<?php

namespace App\Http\Controllers;

use Session;
use ZipArchive;
use Storage;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Illuminate\Http\Request;
use App\File;

class PagesController extends Controller
{
    public function showIndex(){


        return view('welcome');
    }

    public function handleInput(Request $request){

        $originalFileName = $request->file('input')->getClientOriginalName();
        $originalFileName = \explode(".", $originalFileName)[0];

        $file = $request->file('input')->storeAs('public', $originalFileName.'.story');

        $filename = pathinfo($file)['basename'];

        $filepath = \storage_path() . "\\app\\public\\" .$filename;

        $zip = new ZipArchive();

        if($zip->open($file, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE) !== TRUE){
            dd('neki kurac ne radi');
        }
        $zip->open($filepath);
        $zip->extractTo(\storage_path() . "\\app\\public\\extracted");
        $zip->close();

        $imagesPath=[];
        $files = Storage::allFiles('/public/extracted/story/media/');

        foreach($files as $file){
            $extensionone = "jpg";
            $extensiontwo = "png";
            if(substr($file, -3) == $extensionone || substr($file, -3) == $extensiontwo){
                $imagepath = Storage::url($file);
                array_push($imagesPath, $imagepath);
            }

        }

        return view('viewdata')->with(['imagesPath'=>$imagesPath]);
    }

    public function changeImage(Request $request){

        //dd($request);

        $newImage = $request->file('input');

        $imagePath = $request->imagepath;
        
        $imagename = explode('/', $imagePath);
        $imagename = end($imagename);
        $imagePath = implode('/', explode('/', $imagePath, -1));

        $newImage->move(public_path()."/".$imagePath, $imagename);

        $imagesPath=[];
        $files = Storage::allFiles('/public/extracted/story/media/');

        foreach($files as $file){
            $extensionone = "jpg";
            $extensiontwo = "png";
            if(substr($file, -3) == $extensionone || substr($file, -3) == $extensiontwo){
                $imagepath = Storage::url($file);
                array_push($imagesPath, $imagepath);
            }

        }

        return view('viewdata')->with(['imagesPath'=>$imagesPath]);
    }

    public function saveChanges(){
        
        $files = Storage::allFiles('/public/');
        $filename = "";
        $filepath = "";


        foreach($files as $file){
            if(substr($file, -5) == "story"){
                $filename = pathinfo($file)['basename'];

                $filepath = \storage_path() . "\\app\\public\\" .$filename;
            break;
            }
        }

        $this->Zip(storage_path('app\\public\\extracted\\story\\media'), $filepath);

    }

    public function downloadFile($filepath){

        if (file_exists($filepath)) {
            ob_clean();
            ob_end_flush();
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');

            readfile($filepath);

            // if file is downloaded delete all created files from the system
            return redirect('/');
        }
    }

    function Zip($rootPath, $destination){

        $zip = new ZipArchive();
        if($zip->open($destination, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE)){
            // Create recursive directory iterator
            /** @var SplFileInfo[] $files */
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($rootPath),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file)
            {
                // Skip directories (they would be added automatically)
                if (!$file->isDir())
                {
                    // Get real and relative path for current file
                    $filepath = $file->getRealPath();
                    $relativePath = substr($filepath, strlen($rootPath) + 1);

                    // Add current file to archive
                    $zip->addFile($filepath, "/story/media/". $relativePath);
                }
            }

            // Zip archive will be created only after closing object
            $zip->close();
        }
        else{
            echo "karina";
        }

    }

}
