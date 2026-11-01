<?php

/*
|--------------------------------------------------------------------------
| Redirect Awal
|--------------------------------------------------------------------------
| Ketika user membuka:
|
| http://inventory-jwp.test
|
| maka otomatis diarahkan ke halaman login
|
*/

header("Location: auth/login.php");
exit;