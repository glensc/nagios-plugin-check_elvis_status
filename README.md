Nagios plugin: check_elvis_status
=================================

Check [Elvis DAM](http://www.elvisdam.com/) status via admin /server-status json data.

This plugins allows you monitor number and boolean values from server-status json (specified with `-u` option).
Numbers may be expressed with size qualifiers (TB, GB, MB).

It expression is taken via `-e` option, the warning and critical tresholds are configurable with `-w` and `-c` 
options respectively. Additionally `-i` option allows you inverse the comparision logic.

Requires: php 5.0, php-json (if using php 5.2, use json pecl)

Examples
========
  
    ./check_elvis_status -u elvis.json -e '->securityStatus->apiOperationsToday' -m 'API ops' -w 3000 -c 5000
    ELVIS_STATUS: CRITICAL: API ops: 5329
     
    ./check_elvis_status -u elvis.json -e '->securityStatus->apiOperationsToday' -m 'API ops' -w 5000 -c 15000
    ELVIS_STATUS: WARNING: API ops: 5329

    ./check_elvis_status -u elvis.json -e '->securityStatus->apiOperationsToday' -m 'API ops' -w 6000 -c 15000
    ELVIS_STATUS: OK: API ops: 5329
