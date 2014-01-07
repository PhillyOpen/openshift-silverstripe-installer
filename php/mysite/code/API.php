<?php

class API extends Controller {

	private static $allowed_actions = array (
		'trips', 'trip'
	);


	public function init(){
		parent::init();
		$this->Title = 'CyclePhilly.org API';
	}



	public function trip($request){
		//var_dump($request->param('ID'));
		
		//var_dump($tripdetails);
		if ($request->param('ID')) {
			if(is_numeric($request->param('ID'))){
				$this->tripInfo = self::tripInfo($request);
				$this->UserId = $this->tripInfo['user_id'];
				$this->TripId = $request->param('ID');
				switch ($request->param('OtherID')) {
					case 'details':
						# code...
						self::getTripDetails($request);
						break;
					
					default:
						# code...
						self::getTripData($request);
						break;
				}
			}
			
		}
	}

	private function tripInfo($request){
		$points = array();
		$sqlQuery = new SQLQuery();
		$sqlQuery->setFrom('trip');
		$sqlQuery->setSelect(array('user_id','purpose','notes','start','stop','n_coord'));
		$sqlQuery->addWhere('id = '.$request->param('ID'));
		 
		// Get the raw SQL (optional)
		$rawSQL = $sqlQuery->sql();
		 
		// Execute and return a Query object
		$result = $sqlQuery->execute();
		 
		// Iterate over results
		foreach($result as $row) {
		  $points=$row;
		}
		return $points;
	}

	private function getTripData($request){
		$points = array();
		$sqlQuery = new SQLQuery();
		$sqlQuery->setFrom('coord');
		$sqlQuery->setSelect(array('latitude','longitude','recorded','altitude','speed'));
		$sqlQuery->addWhere('trip_id = '.$request->param('ID'));
		 
		// Get the raw SQL (optional)
		$rawSQL = $sqlQuery->sql();
		 
		// Execute and return a Query object
		$result = $sqlQuery->execute();
		 
		// Iterate over results
		foreach($result as $row) {
		  array_push($points,$row);
		}
		$data= array(
			"trip311"=>$points);

		echo json_encode($points);
	}

	private function getUserTrips($userId){
		$points = array();
		$sqlQuery = new SQLQuery();
		$sqlQuery->setFrom('trip');
		$sqlQuery->setSelect(array('id','purpose'));
		$sqlQuery->addWhere('user_id = '.$userId);
		 
		// Get the raw SQL (optional)
		$rawSQL = $sqlQuery->sql();
		 
		// Execute and return a Query object
		$result = $sqlQuery->execute();
		 
		// Iterate over results
		foreach($result as $row) {
		  array_push($points,$row);
		}
		return $points;
	}

	private function getTripDetails($request){
		$points = array();
		$sqlQuery = new SQLQuery();
		$sqlQuery->setFrom('coord');
		$sqlQuery->setSelect(array('recorded','altitude','speed'));
		$sqlQuery->addWhere('trip_id = '.$request->param('ID'));
		 
		// Get the raw SQL (optional)
		$rawSQL = $sqlQuery->sql();
		 
		// Execute and return a Query object
		$result = $sqlQuery->execute();
		// Iterate over results
		$timestamp = array();
		$count = 0;
		$avgspeed = 0;
		$speedTotal = 0;
		foreach($result as $row) {
			//var_dump($row);
			$timestamp[] = $row['recorded'];
			$speedTotal = $speedTotal+$row['speed'];
			++$count;
		  //array_push($points,$row);
		}
		

		$start = $timestamp[0];
		$end = end($timestamp);
		$d_start    = new DateTime($start); 
	    $d_end      = new DateTime($end); 
	    $diff = $d_start->diff($d_end); 
		
		$date = $d_start->format('M d, Y');
		$time = $d_start->format('H:i:s');
		$duration = $diff->format('%h').' hours, '.$diff->format('%i').' minutes, '.$diff->format('%s').' seconds';
		$avgspeed = $speedTotal / $count;
		$trips = self::getUserTrips($this->UserId);
		$data = array(
			"Date" => $date,
			"StartTime" => $time,
			"Purpose" => $this->tripInfo['purpose'],
			"Duration" => $duration,
			"AverageSpeed" => round($avgspeed,2),
			"TotalPoints" => $this->tripInfo['n_coord'],
			"UserTrips" => $trips);
		echo json_encode($data);
	}
    
}
