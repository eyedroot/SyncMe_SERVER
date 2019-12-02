<?php

namespace onair\lib\abstracts;

interface LikeYou 
{
    /**
     * 상대방을 좋아했을 때 
     *
     * @param string $objectId
     * @return boolean
     */
    public static function like(string $objectId) : bool;

    /**
     * 상대방을 싫어 했을 때
     *
     * @param string $objectId
     * @return boolean
     */
    public static function dislike(string $objectId) : bool;
}