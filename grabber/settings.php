<?php 

 /* REST API
 *  For NewsGrabber
 *  Built by Galib Raza
 *  Using Slim Framework
 *  Framework version 3.2
 *  API version 1.0.0
 *  start date 25-june-2019
 *  By Galib Raza Ansari
 */

//USING connection 
$config['db']['host']   = 'localhost';
$config['db']['user']   = 'root';
$config['db']['pass']   = 'root';
$config['db']['dbname'] = 'grabber_database';


//API CONFIGURATIONS
$config['displayErrorDetails'] = true;  //if true => Detailed errors
$config['addContentLengthHeader'] = false;
$config['version'] = '1.0.0';

//GET SERVER protocol
$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://'; 
$site_url = $protocol.$_SERVER['HTTP_HOST']."/";
//LINKS
//site
$GLOBALS['site_url'] =  $site_url;
date_default_timezone_set('asia/kolkata');
