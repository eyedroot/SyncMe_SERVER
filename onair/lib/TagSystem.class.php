<?php

namespace onair\lib;

class TagSystem 
{
    /**
     * 이미 태그가 등록되어 있다면
     */
    const CODE_ALREADY_TAG_EXISTS = 0x05;

    /**
     * 태그가 저정되는 콜렉션
     */
    static $_db_collection = 'syncme.tag_system';

    /**
     * 공백을 제거한 태그의 유니크 아이디를 생성하여 돌려줌
     *
     * @param string $tag 여기서는 태그의 공백을 제거하고 사용한다
     * @return \MongoDB\BSON\ObjectId
     */
    function getTagId(string $tag) : \MongoDB\BSON\ObjectId {
        $db = \handleDB('mongo');
        $tag = trim(\preg_replace('/\s+/i', '', $tag));

        // Upsert로 카운터 증가
        $bulk = new \MongoDB\Driver\BulkWrite();
        $bulk->update(
            [ 'tag' => $tag ],
            [ '$inc' => [ 'count' => 1 ] ],
            [ 'upsert' => true ]
        );

        $r = $db->executeBulkWrite(self::$_db_collection, $bulk);
        $upsertedIds = $r->getUpsertedIds();

        if (count($upsertedIds) > 0) {
            return current($upsertedIds);
        } else {
            $where = ['tag' => $tag];
            $query = new \MongoDB\Driver\Query($where, []);
            $rows = $db->executeQuery(self::$_db_collection, $query)->toArray();

            if ($rows) {
                return $rows[0]->_id;
            }
        }

        return new \MongoDB\BSON\ObjectId();
    }

    /**
     * 태그를 등록 함
     *
     * @param string $cond
     * @param string $tagId ObjectId("")
     * @param string $originTag 공백을 제거하지 않는 실제 태그
     * @return integer
     */
    function put(string $cond, \MongoDB\BSON\ObjectId $tagId, string $originTag) : bool {
        $key = 'tag_' . $cond;
        $tagIdString = (string) $tagId;
        $profile = userProfile()::get(
            app()->session('_id'), [ 'projection' => [$key => true] ]
        );

        $cursors = & $profile->{$key};
        $merge = [];

        if (! is_array($cursors)) {
            $cursors = [];
        }

        // 중복된 태그가 존재하는지 확인하는 플래그
        $isDuplicated = false;

        if (count($cursors) > 0) {
            foreach ($cursors as $row) {
                if ((string) $row['tag_id'] == $tagIdString) {
                    $isDuplicated = true;
                    break;
                }
            }
        }

        if (! $isDuplicated) {
            $merge = array_merge(
                $cursors,
                [ 
                    (object) [ 'tag_id' => new \MongoDB\BSON\ObjectId($tagId), 'origin_tag' => $originTag ] 
                ]
            );

            if (count($merge) > 0) {
                return userProfile()::updateProfile([ $key => $merge ]);
            }
        }

        return false;
    }

    /**
     * 태그를 삭제함
     *
     * @param string $cond
     * @param string $tagIdString
     * @return boolean
     */
    function delete(string $cond, string $tagIdString) : bool {
        $key = 'tag_' . $cond;

        $profile = userProfile()::get(
            app()->session('_id'), [ "projection" => [$key => true] ]
        );

        $cursors = & $profile->{$key};
        $copies = [];

        if (! is_array($cursors)) {
            $cursors = [];
        }

        foreach ($cursors as $row) {
            if ((string) $row['tag_id'] != $tagIdString) {
                $copies[] = $row;
            }
        }
        
        return userProfile()::updateProfile(
            [ $key => $copies ]
        );

        return false;
    }
}