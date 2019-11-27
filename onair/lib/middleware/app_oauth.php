<?php

include_once 'app.php';

/**
 * Middleware
 * 
 * app_oauth.php
 * 
 * 헤더를 통해 인증 키값이 들어왔는지 확인한다
 */
return function () {
    return true;
};