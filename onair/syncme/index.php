<?php 
	include_once 'app.php';

	// $params = toObject(
	// 	handleRequest('gcpid')->disposal('string'),
	// 	handleRequest('os')->disposal('string')
	// );

	app()::HTTP([], [
		app()::POST('/onair/syncme/join', controller('join'))
	]);

	app()::HTTP(
		[ 
			middleware('app_oauth')
		], 
		[
			app()::POST('/onair/syncme/test', controller('app.test')),
			app()::POST('/onair/syncme/login', controller('login'))
		]
	);

	/**
	 * gcpid (index)
	 * connection_date
	 * oauth_key
	 */
	// $bulk->update(
	// 	['gcpid' => $params->gcpid],
	// 	[
	// 		'$set' => [
	// 			'connection_date' => '',
	// 			'oauth_key' => ''
	// 		]
	// 	],
	// 	['upsert' => true]
	// );

	// $result = $mongo->executeBulkWrite('syncme.device_security', $bulk);
	// print_r($result);
