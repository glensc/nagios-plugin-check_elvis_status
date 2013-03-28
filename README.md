Nagios plugin: check_elvis_status
=================================

Check [Elvis DAM](http://www.elvisdam.com/) status via admin /server-status json data.

This plugins allows you monitor number and boolean values from server-status json (specified with `-u` option).
Numbers may be expressed with size qualifiers (TB, GB, MB).

It expression is taken via `-e` option, the warning and critical tresholds are configurable with `-w` and `-c` 
options respectively. Additionally `-i` option allows you inverse the comparision logic.

Requires: php 5.2, [php-json](http://php.net/json) (if using php < 5.2, use [json pecl](http://pecl.php.net/package/json))

Examples
========
  
Check for API Operations count:

    ./check_elvis_status -u elvis.json -e '->securityStatus->apiOperationsToday' -m 'API ops' -w 3000 -c 5000
    ELVIS_STATUS: CRITICAL: API ops: 5329
     
    ./check_elvis_status -u elvis.json -e '->securityStatus->apiOperationsToday' -m 'API ops' -w 5000 -c 15000
    ELVIS_STATUS: WARNING: API ops: 5329

    ./check_elvis_status -u elvis.json -e '->securityStatus->apiOperationsToday' -m 'API ops' -w 6000 -c 15000
    ELVIS_STATUS: OK: API ops: 5329


Check processing queue monitoring if >300 warning, if >500 critical

     ./check_elvis_status.php -u elvis.json -e '->mediaStatus->waitingProcessingRequests' -m 'waitingProcessingRequests' -w 300 -c 500
    ELVIS_STATUS: OK: waitingProcessingRequests: 0

Check if proccessing servers are seen by Elvis:

    ./check_elvis_status.php -u elvis.json -e '->mediaStatus->operationalNodes' -m operationalNodes -v -i -c 3 
    EVAL: return $json->mediaStatus->operationalNodes;
    ELVIS_STATUS: OK: operationalNodes: 3

Check if Processing Servers are Connected & status ready.  
This one converts boolean value to integer to be able to make comparision :)

    ./check_elvis_status.php -u elvis.json -e '->mediaStatus->mediaNodesStatus[0]->ready+0' -m 'ready' -c 1 -i
    ELVIS_STATUS: OK: ready: 1
    ./check_elvis_status.php -u elvis.json -e '->mediaStatus->mediaNodesStatus[0]->ready+0' -m 'ready' -c 1 -i
    ELVIS_STATUS: CRITICAL: ready: 0
    
If you want the same check to give WARNING instead of CRITICAL, setup warning option instead:

    ./check_elvis_status.php -u elvis.json -e '->mediaStatus->mediaNodesStatus[0]->ready+0' -m 'ready' -w 1 -i
    ELVIS_STATUS: OK: ready: 1
    ./check_elvis_status.php -u elvis.json -e '->mediaStatus->mediaNodesStatus[0]->ready+0' -m 'ready' -w 1 -i
    ELVIS_STATUS: WARNING: ready: 0
