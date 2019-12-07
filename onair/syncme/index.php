<?php 
	include_once 'app.php';

	// $params = toObject(
	// 	handleRequest('gcpid')->disposal('string'),
	// 	handleRequest('os')->disposal('string')
	// );

	app()::HTTP([], [
		// 회원가입
		app()::POST('/onair/syncme/join', controller('join')),
		app()::GET('/onair/syncme/test', function () {
			// dd($_SERVER);
			dd(handleDB('mongo'));
		}),

		// 로그인
		// 보안상의 이유로 일반적인 로그인에서 connection-state로 명칭 변경
		app()::POST('/onair/syncme/connection-state', controller('connection.state'))
	]);

	app()::HTTP(
		[ 
			middleware('app_oauth')
		], 
		[
			// app()::POST('/onair/syncme/test', controller('app.test'))
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
