<?php

require 'vendor/autoload.php';

$config = include 'config.php';

use DigitalOceanV2\Adapter\BuzzAdapter;
use DigitalOceanV2\DigitalOceanV2;

// create an adapter with your access token which can be
// generated at https://cloud.digitalocean.com/settings/applications
$adapter = new BuzzAdapter($config['token']);

$parts = explode('.', $config['domain']);
$tld = array_pop($parts);
$name = array_pop($parts);
$ourrecord = implode('.', $parts);
$ourdomain = "$name.$tld";

// create a digital ocean object with the previous adapter
$digitalOcean = new DigitalOceanV2($adapter);

$domainRecord = $digitalOcean->domainRecord();

$domainRecords = $domainRecord->getAll($ourdomain);

$cu = curl_init();
curl_setopt_array($cu, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://tools.binarypenguin.net/myip.php'
));
$ip = curl_exec($cu);

if ($ip) {
	foreach ($domainRecords as $record) {
		if ($record->name == $ourrecord) {
			if ($record->data == $ip) {
				fwrite(STDOUT, "Already up to date\n");
			} else {
				//Delete the old record
				$domainRecord->delete($ourdomain, $record->id);
				//Create the new
				$domainHome = $domainRecord->create($ourdomain, 'A', $ourrecord, $ip);
				fwrite(STDOUT, "Updated to: $ip\n");
			}
			//Done
			exit();
		}
	}

	$domainHome = $domainRecord->create($ourdomain, 'A', $ourrecord, $ip);
	fwrite(STDOUT, "No Record found. Adding $ourrecord.$ourdomain -> $ip\n");

} else {
	fwrite(STDERR, "[" . date("Y-m-d H:i:s") . "] No IP!\n");
}
exit();

