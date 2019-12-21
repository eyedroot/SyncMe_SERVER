<?php

include_once 'app.php';

/**
 * 회원가입 라우터
 */
return function ($entityBody) 
{
  $entity = json_decode($entityBody, JSON_FORCE_OBJECT);
  $clone = $entity;
  $entity = toObject( $entity );

  if (! filter_var($entity->email, FILTER_VALIDATE_EMAIL) ) {
    endpoint( "이메일 형식이 유효하지 않습니다", user()::CODE_ERROR );
  }

  if ( user()::isExists($entity->email) ) {
    endpoint( "이미 가입되어 있는 이메일입니다", user()::CODE_ERROR );
  }

  if (! $entity->password) {
    endpoint( "비밀번호를 입력해주세요", user()::CODE_ERROR );
  }

  if (! $entity->gcpid) {
    endpoint( "인터넷 연결을 확인해주세요", user()::CODE_ERROR );
  }

  if ($oAuthToken = user()::join( $clone )) {
    endpoint( 
      "회원가입이 완료되었습니다", 
      user()::CODE_COMPLETE,
      [ "oauth_token" => $oAuthToken ]
    );
  } else {
    endpoint( "정상적인 수치가 입력되어 있어 회원가입이 불가능합니다", app()::CODE_GLOBAL_FAILURE );
  }
};