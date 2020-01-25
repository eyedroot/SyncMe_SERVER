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
		app()::GET('/onair/syncme/test', function () {
			var_dump(ini_get("session.save_handler"));
			var_dump(ini_get("session.save_path"));
			dd(handleDB('mongo'));
		}),

		// phpinfo
		app()::GET('/onair/syncme/phpinfo', function () {
			phpinfo();
		}),

		app()::GET('/onair/syncme/test2', function () {
			$d = handleDB('mongo');

			$bulk = new \MongoDB\Driver\BulkWrite();
			$bulk->update(
				[ 'tag' => "테스트" ], 
				[ '$inc' => [ 'count' => 1 ] ],
				[ 'upsert' => true ]
			);

			$r = $d->executeBulkWrite('syncme.tag_system', $bulk);
			dd($r->getUpsertedIds());
		}),

		// 상태 확인 및 사용자의 마지막 업데이트 날짜가 1시간 이후라면
		// 해당 사람의 태그 매칭 시스템을 업데이트 함
		app()::POST('/onair/syncme/connection-state', controller('connection.state')),

		// 단말기 인증
		app()::POST('/onair/syncme/device-verification', controller('device.verification'))
	]);

	app()::HTTP(
		[ 
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