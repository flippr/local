<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 8/5/14
 * Time: 3:17 PM
 */

$first_name = strtolower(utf8_encode("josh's"));
$last_name = strtolower(mysql_real_escape_string(utf8_encode("NewEST")));
$email = strtolower(str_replace("'", "", utf8_encode("joshua'st'eve>n's@gmail.com")));
$internal = (int)"1";
$email = filter_var($email, FILTER_SANITIZE_EMAIL);
$params = array (
    'first_name' => mysql_real_escape_string($first_name),
    'last_name'  => $last_name,
    'email'      => $email,
    'internal'   => $internal,
);
// split on @ and return last value of array (the domain)
$domain = $email;

// output domain
echo $domain;
