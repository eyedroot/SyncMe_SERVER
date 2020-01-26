<?php

namespace onair\lib;

class UserProfile
{
    /**
     * 유저 프로파일 정보가 담기는 콜렉션 이름
     */
    static $_db_collection = 'syncme.user_profile';

    /**
     * 사진을 올릴 수 있는 최대 갯수를 지정함
     */
    const MAX_PHOTO_COUNT = 7;

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

        usort($photoUpdator, function ($a, $b) {
            return $a->timestamp <=> $b->timestamp;
        });
        
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
    static function updateProfile(array $pdata) : bool {
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
     * 해당 유저의 
     * 
     * @param string $id
     * @param integer $point
     * @return boolean
     */
    static function likeOrDislike(string $id, int $point) : bool {
        $db    = handleDB('mongo');
        $bulk  = new \MongoDB\Driver\BulkWrite();
        $key   = null;
        $point = ($point > 0) ? 1 : -1;

        if ($point > 0) {
            $key = 'like';
        } else {
            $key = 'dislike';
        }

        $bulk->update(
            [ "user_id" => new \MongoDB\BSON\ObjectId($id) ],
            [ '$inc' => [ $key =>  $point] ]
        );

        return !! $db->executeBulkWrite(self::$_db_collection, $bulk);
    }

    /**
     * 몽고디비 _id로 해당 유저의 프로필 데이터를 가져옴
     *
     * @param string $_id
     * @param array $options
     * @return object
     */
    static function get(string $_id = '', array $options = []) : object {
        if (! $_id) {
            $_id = app()::session('_id');
        }

        $db = handleDB('mongo');

        $where = [
            'user_id' => new \MongoDB\BSON\ObjectId( $_id )
        ];

        $query = new \MongoDB\Driver\Query($where, $options);
        $rows = $db->executeQuery(self::$_db_collection, $query)->toArray();

        if ($rows) {
            $rows = $rows[0];
            
            if (\property_exists($rows, '_id')) {
                $rows->_id = (string) $rows->_id;
            }

            if (\property_exists($rows, 'user_id')) {
                $rows->user_id = (string) $rows->user_id;
            }

            if (\property_exists($rows, 'photo')) {
                if (is_array($rows->photo) && count($rows->photo) > self::MAX_PHOTO_COUNT) {
                    $tmp = array_reverse($rows->photo);
                    $rows->photo = array_reverse(
                        array_splice($tmp, 0, self::MAX_PHOTO_COUNT)
                    );
                }
            }

            foreach (handleTag()->keys() as $tagSelector) {
                if (\property_exists($rows, $tagSelector)) {
                    /**
                     * toString for \MongoDB\BSON\ObjectId()
                     */
                    $toString = function (object $v) {
                        return [
                            'tag_id'     => (string) $v->tag_id,
                            'origin_tag' => $v->origin_tag,
                            '$objectId' => $v->tag_id
                        ];
                    };
    
                    $rows->{$tagSelector} = array_map($toString, $rows->{$tagSelector});
                }
            }
        }

        return $rows;
    }
}