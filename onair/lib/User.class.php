<?php

namespace onair\lib;

class User 
{

    const CODE_ALREADY_EXISTS_USER = 0x02;

    static $_db_table_user = 'syncme.user';

    /**
     * 해당 이메일로 유저가 가입되어 있는지 확인한다
     *
     * @param string $email
     * @return boolean
     */
    static function isExists(string $email) : bool {
        $db = handleDB('mongo');

        $query = new \MongoDB\Driver\Query(['email' => $email]);
        $rows = $db->executeQuery(static::$_db_table_user, $query)->toArray();

        return !! (
            (count($rows) != 0) ? true : false
        );
    }

}