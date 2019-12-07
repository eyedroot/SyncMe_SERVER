<?php

return [
    /**
     * Encrpytion/Decryption Key
     */
    'key' => md5('superhero can safe the world!!'),
    /**
     * MongoDB Settings
     */
    'mongodb_host'     => ($_SERVER['SERVER_NAME'] == 'byzz.app') ? 'localhost' : 'mongo',
    'mongodb_port'     => 27017,
    'mongodb_dbname'   => 'syncme',
    'mongodb_id'       => 'syncme_server',
    'mongodb_password' => 'OQX9BGAYufNqfgiWsgwf6WyIkZKVgBnnlZ8Vh5DmNh/b+6AGpr6a6qtwBPu1V2CbxXzCYg=='
];