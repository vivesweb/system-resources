<?php
/** resources_linux class include
 *
 * 
 * 
 * @author Rafael Martin Soto
 * @author {@link http://www.inatica.com/ Inatica}
 * @since July 2021
 * @version 1.0
 * @license GNU General Public License v3.0
 *
 *	           .888888:.                                        
 *             88888.888.           
 *            .8888888888 
 *            8' `88' `888 
 *            8 8 88 8 888
 *            8:.,::,.:888
 *          .8`::::::'888
 *           88  `::'  888
 *          .88        `888.
 *        .88'   .::.  .:8888.
 *       .888.'   :'    `'88:88.
 *      .8888'    '        88:88.
 *     .8888H,    .        88:888 
 *     `8888HH,   :        8:888
 *      `.:.8HH,  .       .::888
 *     .:::::88H  `      .:::::::.
 *    .::::::.8         .:::::::::
 *    :::::::::..     .:::::::::
 *     `:::::::::88888:::::::'
 *          `:::'       `:' 
 *
 * //  awk '/^Revision/ { print $3 }' /proc/cpuinfo
 *
 *
 * file_get_contents('/proc/meminfo') returns this example array with memory values:
 *
 *  ['MemTotal']=>      string(6) "440448" 
 *  ['MemFree']=>       string(5) "42684" 
 *  ['MemAvailable']=>  string(6) "249796" 
 *  ['Buffers']=>       string(5) "59748" 
 *  ['Cached']=>        string(6) "184296" 
 *  ['SwapCached']=>    string(2) "52" 
 *  ['Active']=>        string(6) "135012" 
 *  ['Inactive']=>      string(6) "213024" 
 *  ['Unevictable']=>   string(2) "16" 
 *  ['Mlocked']=>       string(2) "16" 
 *  ['SwapTotal']=>     string(6) "102396" 
 *  ['SwapFree']=>      string(6) "100092" 
 *  ['Dirty']=>         string(1) "0" 
 *  ['Writeback']=>     string(1) "0" 
 *  ['AnonPages']=>     string(6) "103952" 
 *  ['Mapped']=>        string(5) "51996" 
 *  ['Shmem']=>         string(4) "5840" 
 *  ['KReclaimable']=>  string(5) "27920" 
 *  ['Slab']=>          string(5) "37768" 
 *  ['SReclaimable']=>  string(5) "27920" 
 *  ['SUnreclaim']=>    string(4) "9848" 
 *  ['KernelStack']=>   string(4) "1000" 
 *  ['PageTables']=>    string(4) "2852" 
 *  ['NFS_Unstable']=>  string(1) "0" 
 *  ['Bounce']=>        string(1) "0" 
 *  ['WritebackTmp']=>  string(1) "0" 
 *  ['CommitLimit']=>   string(6) "322620" 
 *  ['Committed_AS']=>  string(7) "1152968" 
 *  ['VmallocTotal']=>  string(6) "573440" 
 *  ['VmallocUsed']=>   string(4) "2640" 
 *  ['VmallocChunk']=>  string(1) "0" 
 *  ['Percpu']=>        string(2) "64" 
 *  ['CmaTotal']=>      string(5) "65536" 
 *  ['CmaFree']=>       string(5) "20576"
 *
 *
 * sys_getloadavg() Return this example array values of average CPU Load (Last minute, Avg 5 minutes, Avg 15 minutes):
 *
 *   [0]=>               float(0.46) 
 *   [1]=>               float(0.41) 
 *   [2]=>               float(0.37)
 */

class resources_linux extends resources
{

/*
    *  Get CPU avg Load
    *  Ex: 
    *   $loadavg =  $this->fgetCPUAvgLoad()
    *   $Cpu1minute     = $loadavg[0];
    *   $Cpu5minutes    = $loadavg[1];
    *   $Cpu15minutes   = $loadavg[2];
    *
    *  @return array sys_getloadavg()
    */ 

    public function fgetCPUAvgLoad(){
        return sys_getloadavg(); // over the last 1, 5 and 15 minutes, respectively
    } // /fgetCPUAvgLoad()
    

    /*
    *  Get CPU Load in %
    *
    *  Collect 2 samples - each with 1 second period
	*  See: https://de.wikipedia.org/wiki/Load#Der_Load_Average_auf_Unix-Systemen
    *
    *  @return double $loadActual
    */ 

    public function fgetCPULoad(){
        $loadActual = 0;

        // Get load cpu
        $statData1 = _getServerLoadLinuxData();
        
        // Wait 1 second
        sleep(1);

        // Get load cpu for 2nth time
        $statData2 = _getServerLoadLinuxData();

        if( (!is_null($statData1)) && (!is_null($statData2)) ){
            // Get difference
            $statData2[0] -= $statData1[0];
            $statData2[1] -= $statData1[1];
            $statData2[2] -= $statData1[2];
            $statData2[3] -= $statData1[3];

            // Sum up the 4 values for User, Nice, System and Idle and calculate
            // the percentage of idle time (which is part of the 4 values!)
            $cpuTime = $statData2[0] + $statData2[1] + $statData2[2] + $statData2[3];

            // Invert percentage to get CPU time, not idle time
            $loadActual = 100 - ($statData2[3] * 100 / $cpuTime);
        }// /if ! is null statData1 & statData2
        
        return $loadActual;
    } // /fgetCPULoad()



    /*
    *  GET MemResources
    *
    *  @return array $items
    */

    public function fGetMemResources( ){
        $MemResources = [];

        if(is_readable('/proc/meminfo')){

            $contents = file_get_contents('/proc/meminfo');
            preg_match_all('/(\w+):\s+(\d+)\s/', $contents, $matches);
            $MemResources = array_combine($matches[1], $matches[2]);
            } // /is_readable('/proc/meminfo')

        return $MemResources;
    }// /fGetMemResources()



    /*
    *  GET TotalHd Space in bytes
    *
    *  @return int $TotalHD
    */

    public function fGetTotalHD( ){
        $UnitPath   = '/';
	    $TotalHD    = disk_total_space($UnitPath);
        return $TotalHD;
    }// /fGetTotalHD()



    /*
    *  GET TotalFreeHd Space in bytes
    *
    *  @return int $TotalFreeHD
    */

    public function fGetTotalFreeHD( ){
        $UnitPath       = '/';
	    $TotalFreeHD    = disk_free_space($UnitPath);
        return $TotalFreeHD;
    }// /fGetTotalFreeHD()



    /*
    *  GET TotalUsedHd Space in bytes
    *
    *  @return int $TotalUsedHD
    */

    public function fGetTotalUsedHD( ){
	    $TotalUsedHD    = $this->fGetTotalHD() - $this->fGetTotalFreeHD();
        return $TotalUsedHD;
    }// /fGetTotalUsedHD()




    /*
    *  GET Uptime String
    *
    *  @return string $uptime
    */

    public function fGetUptime( ){
        $uptime = '';

        if(is_readable('/proc/uptime')){
            $str   = @file_get_contents('/proc/uptime');
            $num   = floatval($str);
            $secs  = $num % 60;
            $num   = (int)($num / 60);
            $mins  = $num % 60;
            $num   = (int)($num / 60);
            $hours = $num % 24;
            $num   = (int)($num / 24);
            $days  = $num;
    
            $uptime = $days.' days, '.$hours.' hours, '.$mins.' minutes & '.$secs.' seconds';
            } // /is_readable('/proc/uptime')

        return $uptime;
    }// /fGetUptime()




    /*
    *  GET CPU TEMPERATURE in ºC X 1000
    *   it returns 53000 for 53ºC
    *
    *  @return int $temperature
    */

    public function fGetCPUTemperature( ){
        $temperature = 0;

        if(is_readable('/sys/class/thermal/thermal_zone0/temp')){
            $str            = @file_get_contents('/sys/class/thermal/thermal_zone0/temp');
            $temperature    = intval($str);
            }

        return $temperature;
    }// /fGetCPUTemperature()




    /*
    *  GET GPU TEMPERATURE in ºC
    *
    *  @return int $temperature
    */

    public function fGetGPUTemperature( ){
        $temperature = 0;

        $temperature = shell_exec ( 'vcgencmd measure_temp' );
        if( isset($temperature) ){
            $temperature = substr( $temperature, 5, -3 ); // Cut left 'temp=' and cut right 'ºC';
            }

        return $temperature;
    }// /fGetGPUTemperature()




    /*
    *  GET OS Version
    *
    *  @return string $OsVersion
    */

    public function fGetOSVersion( ){
        $OsVersion = shell_exec ( 'uname -a' );

        return $OsVersion;
    }// /fGetOSVersion()




    /*
    *  GET Hardware Version
    *
    *  @return string $HdwVersion
    */

    public function fGetHWVersion( ){
        $HdwVersion = '';

        if(is_readable('/proc/device-tree/model')){
            $str        = @file_get_contents('/proc/device-tree/model');
            $HdwVersion = $str;
            }

        return $HdwVersion;
    }// /fGetHWVersion()




    /*
    *  GET Release Distribution
    *
    *  @return string $ReleaseDistrib
    */

    public function fGetReleaseDistrib( ){
        $release_distrib = '';

        if(is_readable('/etc/lsb-release')){
            $release_info       = parse_ini_file("/etc/lsb-release");
            if( isset($release_info["PRETTY_NAME"]) ){
                $release_distrib    = $release_info["PRETTY_NAME"];
            } else {
                if( isset($release_info["DISTRIB_ID"]) ){
                    $release_distrib .= $release_info["DISTRIB_ID"];
                }
                if( isset($release_info["DISTRIB_RELEASE"]) ){
                    $release_distrib .= ", ".$release_info["DISTRIB_RELEASE"];
                }
                if( isset($release_info["DISTRIB_CODENAME"]) ){
                    $release_distrib .= ", ".$release_info["DISTRIB_CODENAME"];
                }
                if( isset($release_info["DISTRIB_DESCRIPTION"]) ){
                    $release_distrib .= ", ".$release_info["DISTRIB_DESCRIPTION"];
                }
            }
        }

        return $release_distrib;
    }// /fGetReleaseDistrib()




    /*
    *  GET Number Of CPU'S
    *
    *  @return string $NumCPUs
    */

    public function fGetNumCPUs( ){
        $NumCPUs = 1; // Default 1 cpu

        if(is_file('/proc/cpuinfo')) {
            $cpuinfo = file_get_contents('/proc/cpuinfo');
            preg_match_all('/^processor/m', $cpuinfo, $matches);
            $NumCPUs = count($matches[0]);
        }

        return $NumCPUs;
    }// /fGetNumCPUs()
} // /resources_linux class
?>