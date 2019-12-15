<?php

include_once 'app.php';

/**
 * 회원가입 라우터
 */
return function ($entityBody) 
{
  $tag = handleRequest('tag')->disposal('int')->get();

  try {
    $hf = handleFile( $_FILES );
  } catch (\ErrorException $e) {
    endpoint( "UPLOAD_FAILED_IN_EXCEPTION", $hf::CODE_ERROR );
  }
  
  if ( ! isset($_FILES) || ! app()->session('_id') ) {
    endpoint( "UPLOAD_FAILED", $hf::CODE_ERROR );
  }

  if ( $hf->integrity() ) {
    if ($result = $hf->upload($hf)) {
      if ( userProfile()::updatePhoto($result) ) {
        $options = $result;
        endpoint( "SUCCESS_UPDATE_USER_PROFILE", user()::CODE_COMPLETE, $options );
      }
    }
  }

  eliminateHandler('handleFile');
  endpoint( "UPLOAD_FAILED_IN_INTEGRITY", $hf::CODE_ERROR );
  
};