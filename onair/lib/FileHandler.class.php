<?php

namespace onair\lib;

class FileHandler
{
    /**
     * 상태코드
     * 
     * 파일업로드가 실패하면 해당 코드를 리턴
     */
    const CODE_ERROR = 0x04;

    /**
     * 업로드가 허용된 파일 타입들
     */
    static $allowedType = [
        "multipart/form-data",
        "image/jpeg",
        "image/png"
    ];

    /**
     * 허용된 파일 확장자
     */
    static $allowedExtension = [
        "jpeg",
        "jpg",
        "png"
    ];

    /**
     * 업로드가되는 디렉토리
     */
    static $directory = "/var/www/SyncMe_UPLOAD/";

    /**
     * 가상의 디렉토리
     * 
     * 추후 용량이 꽉찾을 때 해당 디렉토리와 nginx 세팅만 바꾸면 패스를 변경 할 수 있도록 하는 장치
     */
    static $virtualDir = "z00000000";

    /**
     * $_FILES
     *
     * @var array
     */
    private $files = [];

    /**
     * 파일 확장자
     *
     * @var string
     */
    private $extension = "";

    /**
     * 생성자
     *
     * @param array $files
     */
    function __construct(array $files) {
        if (count($files) > 0) {
            if (\array_key_exists('image', $files)) {
                $this->files = $files['image'];
            } else {
                throw new \ErrorException("\$_FILES 변수에 `image` 키를 찾을 수 없습니다.");
            }

            if (\array_key_exists('name', $this->files)) {
                $this->extension = strtolower(end(explode('.', $this->files['name'])));
            }
        }
    }

    /**
     * 파일 무결성 체크
     *
     * @param array $files
     * @return boolean
     */
    function integrity() : bool {
        if (\array_key_exists('error', $this->files) && $this->files['error'] == 0) {
            if (in_array($this->files['type'], static::$allowedType)) {
                if (in_array($this->extension, static::$allowedExtension)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * 파일 업로드 진행
     *
     * @param array $files
     * @return array
     */
    function upload() : array {
        /**
         * 파일 이름을 만드는 함수
         */
        $touch = function (string $name) {
            if ($name && app()->session('_id')) {
                $t = sprintf('%s-%s-%s', 
                        app()->session('_id'),
                        (string) time(),
                        base64_encode($name)
                    );

                $t = safeEncrypt($t);
                $t = str_replace('/', '$$', $t);

                return $t;
            }

            return '';
        };

        if ( $touchName = $touch($this->files['name']) ) {
            $fullPath = self::$directory . self::$virtualDir . '/' . $touchName . $this->extension;

            if (! file_exists($fullPath)) {
                move_uploaded_file($this->files['tmp_name'], $fullPath);
                $imageSize = getimagesize($fullPath);

                return [
                    "filename"   => $touchName,
                    "filetype"   => $this->extension,
                    "filesize"   => $this->files['size'],
                    "imagesize"  => $imageSize,
                    "virtualdir" => static::$virtualDir
                ];
            }
        }

        return [];                
    }

}