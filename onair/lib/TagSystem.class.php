<?php

namespace onair\lib;

class TagSystem 
{
    /**
     * 태그의 카테고리(`cond`) 해당하는 코드
     */
    static $tagCode = [
        "hobby" => 0x01,
        "food"  => 0x02
    ];

    /**
     * 이미 태그가 등록되어 있다면
     */
    const CODE_ALREADY_TAG_EXISTS = 0x05;

    /**
     * 태그를 등록 함
     *
     * @param string $cond
     * @param string $tag
     * @return integer
     */
    static function put(string $cond, string $tag) : bool {
        $key = 'tag_' . $cond;

        $profile = userProfile()::get(
            app()->session('_id'),
            [ 'projection' => [$key => true] ]
        );

        $merge = [];

        if ($tagCode = static::getCondCode($cond)) {
            if (\property_exists($profile, $key)) {
                if (! is_array($profile->{$key})) {
                    $profile->{$key} = [];
                }

                if (! in_array($tag, $profile->{$key})) {
                    $merge = array_merge($profile->{$key}, [ $tag ]);
                } else {
                    $merge = $profile->{$key};
                }
            } else {
                // 아직 태그가 입력이 되지 않은 회원
                $merge[] = $tag;
            }

            $merge = array_unique($merge);

            return userProfile()::updateProfile(
                [ $key => $merge ]
            );
        }

        return false;
    }

    /**
     * 태그를 삭제함
     *
     * @param string $cond
     * @param string $tag
     * @return boolean
     */
    static function delete(string $cond, string $tag) : bool {
        $key = 'tag_' . $cond;

        $profile = userProfile()::get(
            app()->session('_id'),
            [ "projection" => [$key => true] ]
        );

        $pos = array_search($tag, $profile->{$key});

        if (array_key_exists($pos, $profile->{$key})) {
            unset( $profile->{$key}[$pos] );

            return userProfile()::updateProfile(
                [ $key => $profile->{$key} ]
            );
        }

        return false;
    }

    /**
     * 태그의 카테고리(`cond`)에 해당하는 코드를 리턴함
     *
     * @param string $cond
     * @return integer
     */
    static function getCondCode(string $cond) : int {
        if (array_key_exists($cond, self::$tagCode)) {
            return self::$tagCode[$cond];
        } else {
            return -1;
        }
    }
}