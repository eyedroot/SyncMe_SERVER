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
     * @param float $latitude
     * @param float $longtitude
     * @return boolean
     */
    function updateMatchTable(float $longtitude, float $latitude) : bool {
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
            'age'              => (int) $profile->age,
            'gender'           => (int) $profile->gender, // 0=남자, 1=여자
            'religion'         => $profile->religion,
            'like'             => $profile->like,
            'dislike'          => $profile->dislike,
            'location'         => [
                'type'        => 'Point',
                'coordinates' => [$longtitude, $latitude]
            ],
            'update_timestamp' => new \MongoDB\BSON\UTCDateTime()
        ];

        $bulk->update(
            [ 'user_id' => new \MongoDB\BSON\ObjectId( app()->session('_id') ) ],
            [ '$set' => $updateData ],
            [ 'upsert' => true ]
        );

        return !! $db->executeBulkWrite(self::$_db_collection, $bulk);
    }

    /**
     * 매칭 테이블을 가져옴
     * 
     * TODO: 종교로 매칭되는 시스템 가져와야 한다
     *
     * @param integer $distance
     * @param array $coordinates
     * @param integer $religion
     * @return array
     */
    function getTable(int $distance, array $coordinates, int $religion) : object {
        $db = handleDB('mongo');

        $distance = $distance * 1000;
        list($dbName, $collectionName) = explode('.', self::$_db_collection);

        $command = new \MongoDB\Driver\Command([
            'aggregate' => $collectionName,
            'pipeline' => [
                [
                    '$geoNear' => [
                        'spherical' => true,
                        '$limit' => 20,
                        'maxDistance' => $distance,
                        'near' => [
                            'type' => 'Point',
                            'coordinates' => $coordinates
                        ],
                        'distanceField' => 'distance',
                        'key' => 'location'
                    ]
                ]
            ],
            'cursor' => [ 'batchSize' => 10 ]
        ]); 
        
        $cursor = $db->executeCommand($dbName, $command);
        return $cursor;
    }
}