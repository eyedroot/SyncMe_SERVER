<?php 
	include_once 'app.php';

	// $params = toObject(
	// 	handleRequest('gcpid')->disposal('string'),
	// 	handleRequest('os')->disposal('string')
	// );

	app()::HTTP([], [
		// 회원가입
		app()::POST('/onair/syncme/join', controller('join')),

		// 로그인
		app()::POST('/onair/syncme/login', controller('login')),

		// 연결상태 확인 및 액티비티의 이정표 제공
		app()::POST('/onair/syncme/connection-state', controller('connection.state')),

		// 단말기 인증
		app()::POST('/onair/syncme/device-verification', controller('device.verification')),

		// 단말기위 위치 값을 가져와 업데이트 한다
		app()::POST('/onair/syncme/device-location', controller('device.location'))
	]);

	app()::HTTP(
		[ 
			// 유효한 세션이 있는지 체크하는 미들웨어
			middleware('app_oauth')
		], 
		[
			// 프로파일 이미지 업로드 
			app()::POST('/onair/syncme/upload-photo', controller('profile.upload')),

			// 프로파일 좋아요/싫어요
			app()::POST('/onair/syncme/profile-like-or-dislike', controller('profile.like.dislike')),

			// 프로필 업데이트
			app()::POST('/onair/syncme/profile-update', controller('profile.update')),

			// 프로파일 저장된 데이터 가져오기
			app()::POST('/onair/syncme/profile-preloader', controller('profile.preloader')),

			// 프로필 취미 음식 업데이트
			app()::POST('/onair/syncme/profile-tag', controller('profile.tag')),

			// 로그아웃
			app()::POST('/onair/syncme/logout', controller('logout'))
		]
	);