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
	public function upload(Request $request)
	{	
		$folder = $request->folder;
		$fileName = 'index.html';
	
		$result = $request->file->move(public_path($folder), $fileName);
		return $result;
	}
	
	public function delete(Request $request)
	{
		$folder = $request->folder;
		$this->deleteDir(public_path($folder));
		return true;
	}

	public function deleteDir($dirPath) {
    if (! is_dir($dirPath)) {
			throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
			if (is_dir($file)) {
				self::deleteDir($file);
			} else {
				unlink($file);
			}
    }
    rmdir($dirPath);
	}
}
