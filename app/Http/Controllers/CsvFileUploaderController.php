<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Jobs\CsvFileUpload;
use Illuminate\Support\Facades\Bus;
use Response;
use DB;
use Exception;
use Log;

class CsvFileUploaderController extends Controller
{
    public function getandSaveData(Request $request){

    	dd($request->query('name'));
    	dd( Response::json($request->getContent()));
    	// return { "Name":"prem","Age":2020 };
    	//dd('sdsds');
    }
    public function getSavedFileData(){
    	// dd('dd');
    	//$postdata = @file_get_contents("http://[::1]/spacepointtask/uploads/file/20220416204352.csv");
    	try{
	    	$getFileName = DB::table('csv_file')->where(['id'=>1])->first();
	    	// File Name with extension for the url to send in cURL
	    	$fileName = $getFileName->file_name;
	    	// Removing the .ext from the file because needed the file name only in download function
	    	$fileNameWithOutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '',$getFileName->file_name);

	    	// The third party url from env file.
	    	$baseUrl = env('CSV_FILE_BASE_URL'); 
	    	$url = $baseUrl.$fileName; //"http://[::1]/spacepointtask/uploads/file/20220416204352.csv";
	    	// Calling member function
	    	$this->downloadFile($fileNameWithOutExt,$url, public_path().'/uploads/');
    	}catch(\Exception $e){
    		Log:info($e->getMessage());
    	}
    	//dd($postdata);
    }
    public function file_get_contents_curl( $url ) { 
    	// Getting data from the cURL because the file is exist on onother server
	    $ch = curl_init();   
	    curl_setopt($ch, CURLOPT_HEADER, 0); 
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	    curl_setopt($ch, CURLOPT_URL, $url);  
	    $data = curl_exec($ch); 
	    curl_close($ch); 

	    return $data; 
	} 
	public function downloadFile( $imgName, $url, $path )
	{
	    $data = $this->file_get_contents_curl( $url );
	    file_put_contents( $path.$imgName, $data ); 
	    echo "Yes File downloaded!";
	}
	public function saveTodb(){
		$location = 'uploads'; //Created an "uploads" folder for that 
		$getFileName = DB::table('csv_file')->where(['id'=>1])->first();
	    
	    $fileNameWithOutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '',$getFileName->file_name);
		$filename = $fileNameWithOutExt;
		$filepath = public_path($location . "/" . $filename);
		// Reading file
		// echo $filepath;die;
		// Adding batch for dispatch Job
		//$batch  = Bus::batch([])->dispatch();
		$i = 0;
		$importData_arr = array(); // Read through the file and store the contents as an array
		if(file_exists($filepath)!== FALSE){
			$file = fopen($filepath, "r");
			//Read the contents of the uploaded file 
			while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
				$num = count($filedata);
				if($i==0){
					$i++;
					continue;
				}
				for ($c = 0; $c < $num; $c++) {
					$importData_arr[$i][] = $filedata[$c];
				}
				$i++;
			}
			fclose($file); //Close after reading
		}
		//echo "<pre>";
		//print_r($importData_arr);
		CsvFileUpload::dispatch($importData_arr);
		return "Job get dispatched";

	}
}
