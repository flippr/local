<?php
set_time_limit(0);
chdir(__DIR__);

ini_set("auto_detect_line_endings", true);
//require 'vendor/autoload.php';
//use RemindCloud\Db;

$dbuser = 'jstevens';
$dbpass = 'mmgood85';

$pdo = new PDO('mysql:host=198.211.102.146;dbname=vennli', $dbuser, $dbpass);

$directory = '/Library/WebServer/Documents/local/upload/';

if (isset($_REQUEST['submit']))
{
    if (!file_exists($directory))
    {
        mkdir($directory, 0777, true);
    }

    if (!file_exists($directory . $_FILES["file"]["name"]))
    {
        move_uploaded_file($_FILES["file"]["tmp_name"],
            $directory . $_FILES["file"]["name"]);
    }
    $directory = $directory . $_FILES["file"]["name"];

    $file = fopen($directory, "r");

    try
    {
        $pdo->beginTransaction();
        while ($csv = fgets($file))
        {
            $handle = fopen("php://memory", "rw");
            fwrite($handle, $csv);
            fseek($handle, 0);

            while (($row = fgetcsv($handle)) !== false)
            {
                $guid = (string)time() . uniqid();
                $pdo->exec("INSERT INTO Participant (id, email, status, is_on_blacklist, guid) VALUES ('null', '$row[2]', '0', '0', '$guid')");
                $output[] = $row;
            };
        }
        $pdo->commit();
    }
    catch (Exception $e)
    {
        echo "Failed: " . $e->getMessage();
    }
    fclose($file);


    $fp = fopen("my_file.txt", "x+");
    file_put_contents($fp, print_r($output, true), FILE_APPEND);
    fclose($fp);

}

