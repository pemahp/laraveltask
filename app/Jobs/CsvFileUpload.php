<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DB;

class CsvFileUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $csvFileData;
    public function __construct($fileData)
    {
        $this->csvFileData = $fileData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $csvFileDataArr = $this->csvFileData;
        try{
            if(!empty($csvFileDataArr)){
                foreach ($csvFileDataArr as $csvKey => $csvValue) {
                    // Preparing the values accroding to multiple columns
                    if(!empty($csvValue) && $csvValue[0]){
                        $employee['firstName'] = $csvValue[0];
                        $employee['lastName'] = $csvValue[1];
                        $employee['userName'] = $csvValue[3];
                        $employee['email'] = $csvValue[4];
                        
                        $organization['organizationName'] = $csvValue[2];

                        // Calling helper for getting state ddata by its name from db
                        $address['state'] = getStateId(ucfirst($csvValue[5]));
                        // Calling helper for getting CITY ddata by its name from db
                        $address['city'] = getCityId(ucfirst($csvValue[6]));
                        $address['addressLine1'] = $csvValue[7];
                        $address['zip'] = $csvValue[8];
                        
                        // Storing the data in the DB for now.
                        DB::table('employee')->insert([
                                $employee
                            ]);
                        DB::table('organization')->insert([
                            $organization
                        ]);
                        DB::table('address')->insert([
                            $address
                        ]);
                    }
                }
            }
        }catch(\Exception $e){
            dd($e->getMessage());
            Log:info($e->getMessage());
        }
    }
}
