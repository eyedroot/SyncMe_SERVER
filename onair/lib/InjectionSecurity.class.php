<?php

namespace onair\lib;

/**
 * 모든 Request 변수들을 관리하는 클래스이다
 * 현재는 POST, GET만 지원하지만 RESTful을 지속적으로 추가할 계획이다
 */
class InjectionSecurity
{
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
     * InjectionSecurity의 생성자
     *
     * @param string $method HTTP REQUEST METHOD가 uppercase로 들어온다
     * @param string $key 변수의 key값을 지정한다
     * @return $this
     */
    function __construct(string $method = 'POST', string $key) {
        switch ($method) {
            case 'POST':
                $request = & $_POST;
            break;
            case 'GET':
                $request = & $_GET;
            break;
            default:
            $request = & $_POST;
        }

        $this->key = $key;

        return $this;
    }

    /**
     * 어떤 값으로 변수를 처리할 것인지 결정한다
     *
     * @param string $type [string|integer|float]
     * @return string|integer|float|double
     */
    function disposal(string $type) {
        $callableMethod = '__disposal' . ucfirst(trim(strtolower($type)));      

        if (\method_exists($this, $callableMethod)) {
            return $this->{$callableMethod}();
        }

        throw new \ErrorException("Unknown type");
    }

    /**
     * 현재 
     *
     * @return void
     */
    private function __disposalString() {
        return $this->request[$this->key];
    } 
}