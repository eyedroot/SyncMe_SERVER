<?php

include_once 'app.php';

/**
 * 태그 컨트롤러
 */
return function ($body) {
    $body = toObject( json_decode($body) );

    
    switch ($body->method) {
        case 'add':
            $tagObjectId = handleTag()->getTagId($body->tag);
            $result = handleTag()->put($body->cond, $tagObjectId, $body->tag);

            if ($result) {
                $key = 'tag_' . $body->cond;

                $tags = userProfile()::get(
                    app()->session('_id'),
                    [ 'projection' => [ $key => true, "_id" => false ] ]
                );

                endpoint( "TAG_ADDED", app()::CODE_GLOBAL_COMPLETE, (array) $tags );
            } else {
                endpoint( "TAG_FAILURE", app()::CODE_GLOBAL_FAILURE );
            }
        break;
        case 'delete':
            $result = handleTag()->delete($body->cond, $body->tagIdString);

            if ($result) {
                endpoint( "TAG_REMOVE_COMPLETE", app()::CODE_GLOBAL_COMPLETE );
            } else {
                endpoint( "TAG_REMOVE_FAILURE", app()::CODE_GLOBAL_FAILURE );
            }
        break;
        default:
            endpoint( "I_DONT_KNOW_THIS_CALL", app()::CODE_GLOBAL_FAILURE );
    }
};