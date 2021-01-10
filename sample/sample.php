<?php
require_once __DIR__ . '/../src/client.php';

use NubiCodes\Flussonic\FlussonicClient;

$client = new FlussonicClient('yourusername','yourpassword','http://yourflussonicmediaserverurl.com');

$client->getServerInfo();