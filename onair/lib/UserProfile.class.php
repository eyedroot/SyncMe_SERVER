<?php

namespace onair\lib;

class UserProfile
{
    /**
     * 유저 프로파일 정보가 담기는 콜렉션 이름
     */
    static $_db_collection = 'syncme.user_profile';

    /**
     * 회원정보 업데이트
     *
     * @param array $udata
     * @param array $projection
     * @return void
     */
    static function updatePhoto(array $udata) {
        $db = handleDB('mongo');
        $bulk = new \MongoDB\Driver\BulkWrite();
        $photoUpdator = [];

        // TODO 이렇게 하면 photo 필드만 나오지는 확인
        // `projection`
        $pProfile = self::get(
            app()->session('_id'),
            [ "projection" => ["photo" => 1] ]
        );

        if ( is_object($pProfile) && property_exists($pProfile, 'photo') ) {
            $photoUpdator = $pProfile->photo;
            $photoUpdator[] = (object) $udata;
        } else {
            $photoUpdator[] = $udata;
        }
        
        $bulk->update(
            [ "user_id" => new \MongoDB\BSON\ObjectId( app()->session('_id') ) ],
            [
                '$set' => [ 
                    "user_id"          => new \MongoDB\BSON\ObjectId( app()->session('_id') ),
                    "update_timestamp" => new \MongoDB\BSON\UTCDateTime(),
                    "photo"            => $photoUpdator
                ]
            ],
            [ "upsert" => true ]
        );

        return !! $db->executeBulkWrite(self::$_db_collection, $bulk);
    }

    /**
     * 회원정보 프로필 데이트
     *
     * @param array $pdata
     * @return void
     */
    static function updateProfile(array $pdata) {
        $db = handleDB('mongo');
        $bulk = new \MongoDB\Driver\BulkWrite();

        $merged = array_merge(
            [ 
                "user_id"          => new \MongoDB\BSON\ObjectId( app()->session('_id') ),
                "update_timestamp" => new \MongoDB\BSON\UTCDateTime() 
            ],
            $pdata
        );

        $bulk->update(
            [ "user_id" => new \MongoDB\BSON\ObjectId( app()->session('_id') ) ],
            [ '$set' => $merged ],
            [ "upsert" => true ]
        );

        return !! $db->executeBulkWrite(self::$_db_collection, $bulk);
    }

    /**
     * 몽고디비 _id로 해당 유저의 프로필 데이터를 가져옴
     *
     * @param string $_id
     * @param array $options
     * @return array
     */
    static function get(string $_id, array $options = []) {
        $db = handleDB('mongo');
        $where = [
            'user_id' => new \MongoDB\BSON\ObjectId( $_id )
        ];

        $query = new \MongoDB\Driver\Query($where, $options);
        $rows = $db->executeQuery(self::$_db_collection, $query)->toArray();

        if ($rows) {
            $rows = $rows[0];
        }

        return $rows;
    }
}