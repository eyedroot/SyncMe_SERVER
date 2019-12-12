<?php

namespace onair\lib;

class UserProfile extends \onair\lib\User
{
    /**
     * 유저 프로파일 정보가 담기는 콜렉션 이름
     */
    static $_db_collection = 'syncme.user_profile';

    /**
     * 회원정보 업데이트
     *
     * @param array $udata
     * @return void
     */
    static function updateProfile(array $udata) {
        $db = handleDB('mongo');
        $bulk = new \MongoDB\Driver\BulkWrite();

        $bulk->update(
            [ "user_id" => app()->session('_id') ],
            [ "$set" => [] ],
            [ "$upsert" => true ]
        );

        return !! $db->executeBulkWrite(self::$_db_collection, $bulk);
    }

    /**
     * 몽고디비 _id로 해당 유저의 프로필 데이터를 가져옴
     *
     * @param string $_id
     * @return array
     */
    static function get(string $_id) : array {

    }
}