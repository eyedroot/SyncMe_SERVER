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
    function updateMatchTable() : bool {
        $db      = \handleDB('mongo');
        $bulk    = new \MongoDB\Driver\BulkWrite();

        $profile = \userProfile()->get();
        $tagIds  = [];

        // 분리되어 있는 태그 필드들을 1개의 배열에 모으기
        foreach (handleTag()->keys() as $tagSelector) {
            if (\property_exists($profile, $tagSelector)) {
                foreach ($profile->{$tagSelector} as $row) {
                    if (! ($row['$objectId'] instanceof \MongoDB\BSON\ObjectId)) {
                        $row['$objectId'] = new \MongoDB\BSON\ObjectId($row['$objectId']);
                    }

                    $tagIds[] = $row['$objectId'];
                }
            }
        }

        $updateData = [
            'user_id'          => new \MongoDB\BSON\ObjectId( app()->session('_id') ),
            'tag'              => $tagIds,
            'like'             => $profile->like,
            'dislike'          => $profile->dislike,
            'update_timestamp' => new \MongoDB\BSON\UTCDateTime()
        ];

        $bulk->update(
            [ 'user_id' => new \MongoDB\BSON\ObjectId( app()->session('_id') ) ],
            [ '$set' => $updateData ],
            [ 'upsert' => true ]
        );

        return !! $db->executeBulkWrite(self::$_db_collection, $bulk);
    }
}