<?php

include_once 'app.php';

/**
 * 상태 체크
 */
return function ($entityBody) 
{
    $entity = \toObject( json_decode($entityBody, JSON_FORCE_OBJECT) );

    if (app()::session('_id') && app()::session('email')) {
        $beforeOneHours = date('Y-m-d', strtotime('-1 hours'));

        if (! app()::session('last_login') || app()::session('last_login') < $beforeOneHours) {
            if (handleTagMatch()->updateStatus()) {
                app()::session('last_login', app()->currentDatetime);
            }
        }

        endpoint("CONNECTION_STATE_OKAY", app()::CODE_GLOBAL_COMPLETE);
    } else {
        /**
         * 유효한 세션이 존재하지 않음
         */
        session_destroy();
        endpoint("CONNECTION_STATE_NOT_OKAY", app()::CODE_GLOBAL_FAILURE);
    }
};