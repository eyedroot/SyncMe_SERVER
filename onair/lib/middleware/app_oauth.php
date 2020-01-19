<?php

include_once 'app.php';

/**
 * Middleware
 * 
 * app_oauth.php
 * 
 * 헤더를 통해 인증 키값이 들어왔는지 확인 및 유효한지 체크한다
 */
return function ($body) {
    return \sessionValid();
};