<?php 
	include_once 'app.php';

	$mongo = \handleDB('mongo');
	$bulk = new MongoDB\Driver\BulkWrite();

	$params = \toObject(
		handleRequest('gcpid')->disposal('string'),
		handleRequest('os')->disposal('string')
	);

	dd($params);
	exit;

	/**
	 * gcpid (index)
	 * connection_date
	 * oauth_key
	 */
	$bulk->update(
		['gcpid' => $params->gcpid],
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
