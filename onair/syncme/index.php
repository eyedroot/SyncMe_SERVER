<?php 
	include_once 'app.php';

	const X_DEFAULT_ROUTER_HEADER = '';

	$mongo = handleDB('mongo');
	$bulk = new MongoDB\Driver\BulkWrite();

	$params = toObject(
		handleRequest('gcpid')->disposal('string'),
		handleRequest('os')->disposal('string')
	);

	try {
		app()::POST('/onair/syncme/join', controller('join'));

		if (middleware('app_oauth')) {
			// 해당 로직으로는 첫 번째 URI가 매칭된 후에도 다음
			// 컨트롤러로 넘어가 URI를 비교하는 불필요한 로직이 있음
			// 추후 해당 문제를 수정하면 성능에 좋은 영향을 끼칠 것 같다
			app()::POST('/onair/syncme/test', controller('app.test'));
			app()::POST('/onair/syncme/login', controller('login'));
		}
	} catch (\ErrorException $error) {

	}

	die();

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
