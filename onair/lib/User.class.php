<?php

namespace onair\lib;

class User 
{

    /**
     * 상태코드
     * 
     * 해당 유저가 이미 존재하면 해당 코드를 클라이언트에 전송
     */
    const CODE_ERROR = 0x04;

    /**
     * 상태코드
     * 
     * 실행이 정상적으로 되었을 때의 완료코드
     */
    const CODE_COMPLETE = 0x07;

    /**
     * 유저 정보가 담기는 콜렉션 이름
     */
    static $_db_collection = 'syncme.user';

    /**
     * 해당 이메일로 유저가 가입되어 있는지 확인한다
     *
     * @param string $email
     * @return boolean
     */
    static function isExists(string $email) : bool {
        $db = handleDB('mongo');

        $query = new \MongoDB\Driver\Query(['email' => $email]);
        $rows = $db->executeQuery(static::$_db_collection, $query)->toArray();

        return !! (
            (count($rows) != 0) ? true : false
        );
    }

    /**
     * 회원가입 메서드
     *
     * @param array $entity
     * @return boolean
     */
    static function join(array $entity) : bool {
        $db = handleDB('mongo');

        if ( \array_key_exists('password', $entity) ) {
            $entity['password'] = safeEncrypt( $entity['password'] );
        }

        if (! \array_key_exists('timestamp', $entity)) {
            $entity['timestamp'] = new \MongoDB\BSON\UTCDateTime();
        }

        $bulk = new \MongoDB\Driver\BulkWrite();
        $bulk->insert($entity);

        return !! $db->executeBulkWrite(static::$_db_collection, $bulk);
    }

}