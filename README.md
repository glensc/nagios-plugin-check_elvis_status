Nagios plugin: check_elvis_status
=================================

Check [Elvis DAM](http://www.elvisdam.com/) status via admin /server-status json data.

This plugins allows you monitor any integer value from server-status json (specified with `-u` option),
it takes expression via `-e` option, the warning and critical tresholds configurable with `-w` and `-c` 
options respectively.

Requires: php 5.0, php-json (if using php 5.2, use json pecl)

Usage
=====
  
    ./check_elvis -u elvis.json -e '->securityStatus->apiOperationsToday' -m 'API ops' -w 3000 -c 5000
    ELVIS: CRITICAL: API ops: 5329
     
    ./check_elvis -u elvis.json -e '->securityStatus->apiOperationsToday' -m 'API ops' -w 5000 -c 15000
    ELVIS: WARNING: API ops: 5329

    ./check_elvis -u elvis.json -e '->securityStatus->apiOperationsToday' -m 'API ops' -w 6000 -c 15000
    ELVIS: OK: API ops: 5329
