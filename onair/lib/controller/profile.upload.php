<?php

include_once 'app.php';

/**
 * 회원가입 라우터
 */
return function ($entityBody) 
{
  $entity = \toObject(
    json_decode($entityBody, JSON_FORCE_OBJECT)
  );

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
      if ( user()::updateProfile($result) ) {
        endpoint( "SUCCESS_UPDATE_USER_PROFILE", user()::CODE_COMPLETE )
      }
    }
  }

  eliminateHandler('handleFile');
  endpoint( "UPLOAD_FAILED_IN_INTEGRITY", $hf::CODE_ERROR );
  
};