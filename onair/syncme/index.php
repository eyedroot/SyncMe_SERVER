<?php 
	include_once 'app.php';

	$mongo = getDBInstance('mongo');
	$bulk = new MongoDB\Driver\BulkWrite();

	$gcpid = handleRequest('gcpid')->disposal('string');
	var_dump($gcpid);
	exit;

	/**
	 * gcpid (index)
	 * connection_date
	 * oauth_key
	 */
	$bulk->update(
		['gcpid' => $_POST['gcpid']],
		[
			'$set' => [
				'connection_date' => '',
				'oauth_key' => ''
			]
		],
		['upsert' => true]
	);

	$result = $mongo->executeBulkWrite('syncme.device_security', $bulk);
	print_r($result);
