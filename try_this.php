<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 7/21/14
 * Time: 12:18 PM
 */

/*$handle = fopen('/Library/WebServer/Documents/local/MOCK_DATA.csv', 'r'); //open big file with fopen
$f = 1; //new file number

while (!feof($handle))
{
    $newfile = fopen('/Library/WebServer/Documents/local/' . $f . '.csv', 'w'); //create new file to write to with file number
    for ($i = 1; $i <= 500; $i++) //for 5000 lines
    {
        $import = fgets($handle);
        fwrite($newfile, $import);
        if (feof($handle))
        {
            break;
        } //If file ends, break loop
    }
    fclose($newfile);
    //MySQL newfile insertion stuff goes here
    $f++; //Increment newfile number
}
fclose($handle);*/

ini_set('auto_detect_line_endings', TRUE);

// Store the line no:
$i = 0;
// Store the output file no:
$file_count = 1;
// Create a handle for the input file:
$input_handle = fopen('/Library/WebServer/Documents/local/MOCK_DATA.csv', "r") or die("Can't open output file.");
// Create an output file:
//$output_handle = fopen('respondents-' . $file_count . '.csv', "w") or die("Can't open output file.");
$temp = tmpfile();
// Loop through the file until you get to the end:
while (!feof($input_handle))
{
    // Read from the file:
    $buffer = fgets($input_handle);
    // Write the read data from the input file to the output file:
    fwrite($output_handle, $buffer);
    // Increment the line no:
    $i++;
    // If on the 5000th line:
    if ($i == 350)
    {
        // Reset the line no:
        $i = 0;
        // Close the output file:
        fclose($output_handle);
        // Increment the output file count:
        $file_count++;
        // Create the next output file:
        $output_handle = fopen('respondents-' . $file_count . '.csv', "w") or die("Can't open output file.");
    }
}
// Close the input file:
fclose($input_handle);
// Close the output file:
fclose($output_handle);



/*$input = fopen("/Library/WebServer/Documents/local/MOCK_DATA.csv", "r");
$output = fopen('respondents-' . $file_count . '.csv', "w") or die("Can't open output file.");

while (!feof($input))
{

    $first_name = utf8_encode($data[0]);
    $last_name = utf8_encode($data[1]);
    $email = utf8_encode($data[2]);
    $internal = (int)$data[3];

    $params = array (
        'first_name' => $first_name,
        'last_name'  => $last_name,
        'email'      => $email,
        'internal'   => $internal,
    );

    print_r($params);
}

fclose($handle);*/