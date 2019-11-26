<?php

namespace onair\lib\abstract;

/**
 * AppFacadeAbstract
 * 
 * AppFacade의 추상 클래스
 * 구현 가능한 메서드들을 나열해보고 테스트를 주 목적으로 한다
 */
abstract class AppFacadeAbstract {

    /**
     * singleton으로 관리되는 모든 클래스의 또 다른 인스턴트가 필요할 때
     * 사용되는 메서드이다
     * 
     * 어떠한 방식으로 static 변수에 담긴 인스턴트를 찾을지
     * 어떠한 방식으로 새로 생성한 static 변수를 담을지
     * 조금은 고민이 더 필요한 메서드이다
     *
     * @return mixed
     */
    abstract function clone();

}