<?php 
	include_once 'app.php';

	const X_DEFAULT_ROUTER_HEADER = '';

	$mongo = \handleDB('mongo');
	$bulk = new MongoDB\Driver\BulkWrite();

	$params = \toObject(
		handleRequest('gcpid')->disposal('string'),
		handleRequest('os')->disposal('string')
	);

	dd($params);

	if (\handlerHeader('HTTP_X_ROUTER_CALLED') === \app('default_router')) {

		// route
		// POST /onair/syncme/test
		app()::POST('/onair/syncme/test', \controller('test'));
		app()::POST('/onair/syncme/test2', \controller('test2'));

	}
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
