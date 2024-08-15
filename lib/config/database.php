<?php
function db($conn) {
    return array(
        'sqlsrv_ci' => array(
            'driver' => 'sqlsrv',
            'host' => '10.100.100.21',
			'port'      => '1433', //1433 4545
            'database' => 'db_central_input',
            'username' => 'sa',
            'password' => 'padm4.4',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ),
        'sqlsrv_ilv' => array(
            'driver' => 'sqlsrv',
            'host' => '10.100.100.20',
			'port'      => '1433', //1433 4545
            'database' => 'db_ilv_padma',
            'username' => 'sa',
            'password' => 'padm4.4',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ),
        'sqlsrv_hris' => array(
            'driver' => 'sqlsrv',
            'host' => 'hris.int.padmatirtagroup.com',
			'port'      => '1433', //1433 4545
            'database' => 'db_hris',
            'username' => 'hris1',
            'password' => 'P4dma_hris',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ),
        'mysql_ilv' => array(
            'driver' => 'mysql',
            'host' => '10.100.100.20',
			'port'      => '3306',
            'database' => 'dashboard_ilv',
            'username' => 'iwan',
            'password' => 'i021172sIS',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ),
        'mysql_ftm' => array(
            'driver' => 'mysql',
            'host' => '10.50.1.22',
			'port'      => '3306',
            'database' => 'ftm',
            'username' => 'it',
            'password' => 'it.45',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ),
        'mysql_fp' => array(
            'driver' => 'mysql',
            'host' => '10.50.1.23',
			'port'      => '3309',
            'database' => 'fin_pro',
            'username' => 'it',
            'password' => 'it.45',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ),
        'mysql_hris' => array(
            'driver' => 'mysql',
            'host' => 'hris.int.padmatirtagroup.com',
			'port'      => '3306',
            'database' => 'dashboard_hris',
            'username' => 'it',
            'password' => 'padm4.4',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ),
    );
}
