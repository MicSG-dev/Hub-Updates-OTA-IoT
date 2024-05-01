<?php
define('database-acesso-privado-rv$he', TRUE);

require('./private/database.php');
require('./private/credentials.php');
require('./private/vendor/autoload.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key as KeyCrypto;

