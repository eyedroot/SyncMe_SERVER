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
    'mongodb_id'       => 'syncme',
    'mongodb_password' => '3fwPhddmtkHu4N+TnmmObzXI0y/sySSdGmYd/ZVqZOxe2pGMArZsptTYc4MUuy35Wz2s5w=='
];