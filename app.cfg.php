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
    'mongodb_password' => 'THL8MZ8kLqHTJetYts8maW70n/602id6+x9WXHq7/zydV0s2Y60/lnwMSegKuNEb/hmqNA=='
];