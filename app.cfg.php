<?php

return [
    /**
     * Encrpytion/Decryption Key
     */
    'key' => md5('superhero can safe the world!!'),
    /**
     * MongoDB Settings
     */
    'mongodb_host'     => (isProd()) ? 'localhost' : 'mongo',
    'mongodb_port'     => 27017,
    'mongodb_dbname'   => 'syncme',
    'mongodb_id'       => 'syncme_server',
    'mongodb_password' => 'OQX9BGAYufNqfgiWsgwf6WyIkZKVgBnnlZ8Vh5DmNh/b+6AGpr6a6qtwBPu1V2CbxXzCYg==',
    /**
     * Redis Settings
     */
    'redis_host'    => (isProd()) ? '127.0.0.1' : 'redis',
    'redis_port'    => 6379,
    'redis_timeout' => 1
];