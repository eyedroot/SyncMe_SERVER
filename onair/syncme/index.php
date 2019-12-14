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
			// echo 19; exit;
			phpinfo();
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
			// 프로파일 이미지 업로드 
			app()::POST('/onair/syncme/upload-photo', controller('profile.upload')),
			// 프로필 업데이트
			app()::POST('/onair/syncme/profile-update', controller('profile.update')),
			// 프로필 취미 음식 업데이트
			app()::POST('/onair/syncme/profile-condition', controller('profile.condition'))
		]
	);