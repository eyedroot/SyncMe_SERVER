<?php

namespace onair\lib;

/**
 * 모든 Request 변수들을 관리하는 클래스이다
 * 현재는 POST, GET만 지원하지만 RESTful을 지속적으로 추가할 계획이다
 */
class InjectionSecurity
{
    /**
     * 내부에서 사용되는 메서드에 대한 Prefix
     */
    private const __INNERFIX_PREFIX__ = '__disposal';

    /**
     * $_GET 또는 $_POST의 주소값이 담긴다
     */
    protected $request;

    /**
     * 변수의 키값이 담긴다
     *
     * @var string
     */
    protected $key;

    /**
     * 현재 키에 해당되는 값이 담긴다
     *
     * @var string
     */
    protected $value;

    /**
     * 실행될 메서드의 이름이 담긴다
     *
     * @var string
     */
    private $disposalMethod;

    /**
     * InjectionSecurity의 생성자
     *
     * @param string $method HTTP REQUEST METHOD가 uppercase로 들어온다
     * @param string $key 변수의 key값을 지정한다
     * @return $this
     */
    function __construct(string $method = 'POST', string $key) {
        switch ($method) {
            case 'POST':
                $this->request = & $_POST;
            break;
            case 'GET':
                $this->request = & $_GET;
            break;
            default:
            $this->request = & $_POST;
        }

        $this->key = $key;
        $this->value = $this->request[ $key ];

        return $this;
    }

    /**
     * 어떤 값으로 변수를 처리할 것인지 결정한다
     *
     * @param string $type [string|integer|float]
     * @return void
     */
    function disposal(string $type) {
        $callableMethod = ( static::__INNERFIX_PREFIX__ . ucfirst(trim(strtolower($type))) ); 

        if (\method_exists($this, $callableMethod)) {
            $this->disposalMethod = $callableMethod;

            return $this;
        }

        throw new \ErrorException("Unknown type");
    }

    /**
     * 현재 설정된 값을 리턴함
     *
     * @return string|integer|float|double
     */
    function get() {
        if ($this->disposalMethod) {
            return $this->{ $this->disposalMethod }();
        }

        return false;
    }

    /**
     * 현재 지정한 키의 이름을 돌려줌
     *
     * @return string
     */
    function getKey() : string {
        return $this->key;
    }

    /**
     * 문자열 값인지 확인함
     *
     * @return boolean
     */
    function isLetter(\mixed $v) : bool {
        return !! (
            ! is_int($v) &&
            ! is_null($v) &&
            ! is_bool($v) &&
            ! is_array($v) &&
            ! is_object($v) &&
            ! is_resource($v)
        );
    }

    /**
     * 이스케이프한 문자열을 돌려줌
     *
     * @return string
     */
    private function __disposalEscape() {
        return str_replace(
            ['\\', "\0", "\n", "\r", "'", '"', "\x1a"], 
            ['\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'], 
            $this->value
        );
    } 

    /**
     * 이스케이프 하지 않는 문자열을 돌려줌
     *
     * @return string|false
     */
    private function __disposalString() {
        return $this->isLetter($this->value) ? strval($this->value) : false;
    }

    /**
     * 정수형 타입을 리턴
     *
     * @return integer|false
     */
    private function __disposalInt() {
        return is_numeric($this->value) && is_int($this->value + 0) ? intval($this->value) : false;
    }

    /**
     * 실수형 타입을 리턴
     *
     * @return float|false
     */
    private function __disposalFloat() {
        return is_numeric($this->value) && is_float($this->value) ? floatval($this->value) : false;
    }
}