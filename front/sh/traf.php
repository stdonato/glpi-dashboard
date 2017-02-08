<?php

	 $interval = 2;	
		
    $tx_path = 'cat /sys/class/net/eth0/statistics/tx_bytes';
    $rx_path = 'cat /sys/class/net/eth0/statistics/rx_bytes';

    $tx_start = intval(shell_exec($tx_path));
    $rx_start = intval(shell_exec($rx_path));

    sleep($interval);

    $tx_end = intval(shell_exec($tx_path));
    $rx_end = intval(shell_exec($rx_path));

    $result['tx'] = round(($tx_end - $tx_start)/1024, 2);
    $result['rx'] = round(($rx_end - $rx_start)/1024, 2);
    
    //echo json_encode($result);
	 echo "TX: ".$result['tx']." KB/s<br>";    
    echo "RX: ".$result['rx']." KB/s";
    
    
    /*
    		<li class="data-row">
			<span class="data-name" style="color: #cecece;">NET:</span>
			<span class="data-value">'; include './sh/traf.php'; 
		
echo		'</span>
		</li>
		
    */
	