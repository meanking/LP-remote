<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Madzipper;
class UploadController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
    }

	/**
	 * Extract a zip file
	 * 
	 */
    public function extract_zip(Request $request)
    {
		$fileOriginalName = $request->files->get('file')->getClientOriginalName();
		$ext      = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		$fileName = time().'.'.$ext;
		
		$uploads_folder = "";
		if ($ext != 'zip') {
			if ($ext == 'pdf') {
				$uploads_folder = "p";
			} else if ($ext == 'jpg' 
					|| $ext == 'gif' 
					|| $ext == 'png') {
				$uploads_folder = "i";
			} else if ($ext == 'mp3' 
					|| $ext == 'wav') {
				$uploads_folder = "a";
			} else if ($ext == 'stl') {
				$uploads_folder = "s";
			} else if ($ext == 'ttf' 
					|| $ext == 'TTF'
					|| $ext == 'woff' 
					|| $ext == 'WOFF'
					|| $ext == 'svg' 
					|| $ext == 'SVG'
					|| $ext == 'eot'
					|| $ext == 'EOT') {
				$uploads_folder = "f";
			} else {
				$uploads_folder = "o";
			}
		}
   
        $result = $request->file->move(public_path($uploads_folder), $fileName);
		if ($result) {
			if ($ext == 'zip') {
				$flag = false;
				while($flag == false) {
					if (file_exists(public_path($uploads_folder).'/'.$fileName)) {
						$flag = true;
						$Path = public_path($uploads_folder).'/'.$fileName;
						$result = \Madzipper::make($Path)->extractTo(public_path($uploads_folder));
						$guid = uniqid();
						$old = public_path($uploads_folder).'/content';
						$new = public_path($uploads_folder).'/'.$guid;
						rename($old, $new);
						return ($guid."/");
					}
				}
			} else {
				return $uploads_folder."/".$fileName;
			}
		}
   
	}
	
	public function delete_file(Request $request)
	{
		$file_path = $request->input('file_path');
		if (strpos($file_path, '/image/') !== false 
			|| strpos($file_path, '/pdf/') !==false
			|| strpos($file_path, '/audio/') !==false
			|| strpos($file_path, '/stl/') !==false
			|| strpos($file_path, '/font/') !==false
			|| strpos($file_path, '/other/') !==false
			|| strpos($file_path, '/i/') !==false
			|| strpos($file_path, '/p/') !==false
			|| strpos($file_path, '/a/') !==false
			|| strpos($file_path, '/s/') !==false
			|| strpos($file_path, '/f/') !==false
			|| strpos($file_path, '/o/') !==false) 
		{
			$path = public_path().$file_path;
			if (file_exists($path)) {
				@unlink($path);
				return true;
			} else {
				return false;
			}
		} else {
			$old = public_path().'/'. str_replace('/', '', $file_path);
			$new = public_path().'/'. str_replace('/', '', $file_path).'_deleted';
			rename($old, $new);
			return true;
		}
	}
}
