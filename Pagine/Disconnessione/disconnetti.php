<?php
session_start();

session_unset();
session_destroy();
setcookie('rememberme',null,0);

echo "Ti sei disconnesso correttamente";
header('location: http://bicicletta22235id.altervista.org/')
?>