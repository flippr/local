<?php

$username = 'root';
$password = 'jeremaroot';
$conn = new PDO('mysql:host=prod-db.c04plts19dwi.us-east-1.rds.amazonaws.com;dbname=higherme', $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$data = $conn->query('SELECT * FROM applications where distance is null');
while ($row = $data->fetch())
{
    $distance = getDistance($row['user_id'], $row['store_id']);

    $stmt = $conn->prepare('UPDATE applications SET distance = :distance WHERE id = :id');
    $stmt->execute(array(
        ':id' => $row['id'],
        ':distance' => $distance
    ));
}

function getUserAddress($id)
{
    try
    {
        $username = 'root';
        $password = 'jeremaroot';
        $conn = new PDO('mysql:host=prod-db.c04plts19dwi.us-east-1.rds.amazonaws.com;dbname=higherme', $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare('SELECT * FROM users_addresses WHERE id = :id');
        $stmt->execute(array('id' => $id));

        $userAddress = $stmt->fetchAll();
        if (count($userAddress))
        {
            foreach ($userAddress as $row)
            {
                $address = $row['street'] . ' ' . $row['zipcode'];
            }
            return $address;
        } else
        {
            return null;
        }
    } catch (Exception $e)
    {
        return null;
    }


}

function getStoreAddress($id)
{
    try
    {
        $username = 'root';
        $password = 'jeremaroot';
        $conn = new PDO('mysql:host=prod-db.c04plts19dwi.us-east-1.rds.amazonaws.com;dbname=higherme', $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare('SELECT * FROM stores WHERE id = :id');
        $stmt->execute(array('id' => $id));
        $stores = $stmt->fetchAll();
        if (count($stores))
        {
            foreach ($stores as $row)
            {
                $address = $row['address'] . ' ' . $row['zipcode'];
            }
            return $address;
        } else
        {
            return null;
        }
    } catch (Exception $e)
    {
        return null;
    }

}

function getDistance($user_address_id, $store_address_id)
{
    $to = getUserAddress($user_address_id);
    $from = getStoreAddress($store_address_id);

    if (($to != null) && ($from != null))
    {
        $url = "http://maps.googleapis.com/maps/api/distancematrix/json?origins=" . str_replace(' ', '+', $to) . "&destinations=" . str_replace(' ', '+', $from) . "&mode=driving&units=imperial&sensor=false";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_all = json_decode($response);
        if ($response_all->status === 'INVALID_REQUEST')
        {
            $distance = 'NA';
        }
        else
        {
            $distance = $response_all->rows[0]->elements[0]->distance->text;
        }
    }
    else
    {
        $distance = 'NA';
    }

    return $distance;
}