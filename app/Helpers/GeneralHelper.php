<?php 
if(!function_exists('getStateId')){
	function getStateId($stateName=''){
		$states = DB::table('state')->where(['stateName'=>$stateName])->select('stateId')->first(); 
		if($states)
			return $states->stateId;
	}
}

if(!function_exists('getCityId')){
	function getCityId($cityName=''){
		$city = DB::table('city')->where(['cityName'=>$cityName])->select('cityId')->first(); 
		// echo $city->cityId;die;
		if($city)
			return $city->cityId;
	}
}
