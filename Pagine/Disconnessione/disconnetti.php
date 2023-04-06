<?php
session_start();

session_unset();
session_destroy();

echo "Ti sei disconnesso correttamente";
header('location: http://bicicletta22235id.altervista.org/')
?>