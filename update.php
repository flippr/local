<?php
try 
{
	$username = 'root';
	$password = 'jeremaroot';
	$conn = new PDO('mysql:host=prod-db.c04plts19dwi.us-east-1.rds.amazonaws.com;dbname=higherme', $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$data = $conn->query('SELECT * FROM users_addresses WHERE longitude is null and id = 19');

	foreach($data as $row)
	{
		$id = $row['id'];
        $address = $row['street']. ' ' . $row['city']. ' ' .$row['zipcode']; 
        $lat = getLatitude($address);
        $long = getLongitude($address);

        if($lat != null && $long != null)
        {
			$stmt = $conn->prepare('UPDATE users_addresses SET lat = :lat and longitude = :long WHERE id = :id');
			$stmt->execute(array(
				':id'   => $id,
				':lat' => $lat,
				':long' => $long
			));
		}

    }
    echo 'done';
} 
catch(PDOException $e) 
{
	echo 'ERROR: ' . $e->getMessage();
}


function getLatitude($add)
{
	     // Get lat and long by address         
        $address = $add; // Google HQ
        $prepAddr = str_replace(' ','+',$address);
        $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
        $output= json_decode($geocode);
        return $output->results[0]->geometry->location->lat;
        //$output->results[0]->geometry->location->lng;
}

function getLongitude($add)
{
	     // Get lat and long by address         
        $address = $add; // Google HQ
        $prepAddr = str_replace(' ','+',$address);
        $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
        $output= json_decode($geocode);
        //$latitude = $output->results[0]->geometry->location->lat;
        return $output->results[0]->geometry->location->lng;
}