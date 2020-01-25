<?php

namespace onair\lib;

class TagMatchSystem 
{
    /**
     * Collection's name
     */
    static $_db_collection = 'syncme.tag_match';

    /**
     * 생성자
     */
    function __construct() {}

    /**
     * 현재 로그인된 사람의 위치를 업데이트 시킴
     *
     * @return boolean
     */
    function updateStatus() : bool {
        $db      = \handleDB('mongo');
        $bulk    = new \MongoDB\Driver\BulkWrite();
        $profile = \userProfile()->get();
        $tagIds  = [];

        foreach (handleTag()->keys() as $tagSelector) {
            if (\property_exists($profile, $tagSelector)) {
                foreach ($profile->{$tagSelector} as $row) {
                    $tagId = new \MongoDB\BSON\ObjectId($row['tag_id']);
                    $tagIds[] = $tagId;
                }
            }
        }

        return true;
    }
}