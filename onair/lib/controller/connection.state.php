<?php

include_once 'app.php';

/**
 * 상태 체크
 */
return function ($entityBody) 
{
    $entity = \toObject( json_decode($entityBody, JSON_FORCE_OBJECT) );

    if (app()::session('_id') && app()::session('email')) {
        // $beforeOneHours = date('Y-m-d', strtotime('-1 hours'));

        // if (! app()::session('last_login') || app()::session('last_login') < $beforeOneHours) {
        //     if (handleTagMatch()->updateStatus()) {
        //         app()::session('last_login', app()->currentDatetime);
        //     }
        // }

        /**
         * TODO: 여기서 앱이 어떤 액티비티를 실행할지에 대한 이정표 코드를 제공해준다.
         * 로그인이 되어 있는 세션에 메인화면에서 현재는 무조건 InputActivity로 가지만
         * 필수 회원정보가 입력되어 있다면 FragmentMatch로 이동시켜주게 한다
         */
        $openActivity = 'activity.container';

        if (! app()::session('profile_age') || ! app()::session('profile_nickname')) {
            $openActivity = 'activity.input';
        }

        endpoint("CONNECTION_STATE_OKAY", app()::CODE_GLOBAL_COMPLETE, [ 'openActivity' => $openActivity ]);
    } else {
        /**
         * 유효한 세션이 존재하지 않음
         */
        session_destroy();
        endpoint("CONNECTION_STATE_NOT_OKAY", app()::CODE_GLOBAL_FAILURE);
    }
};