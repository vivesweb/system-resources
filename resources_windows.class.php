<?php
/** resources_windows class include
 *
 * 
 * 
 * @author Rafael Martin Soto
 * @author {@link http://www.inatica.com/ Inatica}
 * @since July 2021
 * @version 1.0
 * @license GNU General Public License v3.0
 *
 *	
 *	            ,-~Â¨^  ^Â¨-,           
 *           /          / ;^-._...,Â¨/
 *          /          / /         /
 *         /          / /         /
 *        /          / /         /
 *       /,.-:''-,_ / /         /
 *       _,.-:--._ ^ ^:-._ __../
 *     /^         / /Â¨:.._Â¨__.;
 *    /          / /      ^  /
 *   /          / /         /
 *  /          / /         /
 * /_,.--:^-._/ /         /
 *^            ^Â¨Â¨-.___.:^ 
 *
 *
 *	
 *
 * In Windows there are differents commands that give us a lot of information about the hardware:
 *
 *    BIOS:
 *    WMIC /Output:STDOUT  BIOS get /all /format:LIST
 *
 *    CPU:
 *    WMIC /Output:STDOUT  CPU get /all /format:LIST
 *
 *    CD-ROM:
 *    WMIC /Output:STDOUT  CDROM get /all /format:LIST
 *
 *    ETHERNET:
 *    WMIC /Output:STDOUT  NICCONFIG get /all /format:LIST
 *
 *    OPERATING SYSTEM:
 *    WMIC /Output:STDOUT  COMPUTERSYSTEM get /all /format:LIST
 *
 *    HARD DISK:
 *    WMIC /Output:STDOUT  DISKDRIVE get /all /format:LIST
 *
 *    LOGIC DRIVES:
 *    WMIC /Output:STDOUT  LOGICALDISK get /all /format:LIST
 *
 *    RAM:
 *    WMIC /Output:STDOUT  MEMPHYSICAL get /all /format:LIST
 *
 *    SERVICES:
 *    WMIC /Output:STDOUT  SERVICE get /all /format:LIST
 *
 *    PROCESS:
 *    WMIC /Output:STDOUT  PROCESS get /all /format:LIST
 *
 *    STARTUP PROGRAMS:
 *    WMIC /Output:STDOUT  STARTUP get /all /format:LIST
 *
 *    MOTHERBOARD DEVICES:
 *    WMIC /Output:STDOUT  ONBOARDDEVICE get /all /format:LIST
 *
 *    OPERATING SYSTEM ERRORS:
 *
 *    WMIC /Output:STDOUT  RECOVEROS get /all /format:LIST
 *
 *    TO DO STATIC IP:
 *    wmic nicconfig where index=9 call enablestatic("192.168.16.4"), ("255.255.255.0")
 *
 *
 *    https://norfipc.com/comandos/informacion-pc-uso-wmic.html
 */

class resources_windows extends resources
{
   /*
    *  Get CPU Load in %
    *
    *
    *  @return double $loadActual
    */ 

    public function fgetCPULoad(){
        $load = null;
	
        $cmd = 'wmic cpu get loadpercentage /all';
        @exec($cmd, $output);

        if ($output){
            foreach ($output as $line){
                if ($line && preg_match("/^[0-9]+\$/", $line)){
                    $load = $line;
                    break;
                    }
                } // /foreach output
        } // /IF $output

        return $load
    } // /fgetCPULoad()


    /*
    *  GET TotalHd Space in bytes
    *
    *  @return int $TotalHD
    */

    public function fGetTotalHD( ){
        $UnitPath   = substr($_SERVER['DOCUMENT_ROOT'], 0, 2);
	    $TotalHD    = disk_total_space($UnitPath);
        return $TotalHD;
    }// /fGetTotalHD()


    /*
    *  GET fGetHWVersion
    *
    *  @return string
    */

    public function fGetHWVersion( ){
        return 'Not Available';
    }// /fGetHWVersion()




    /*
    *  GET fGetReleaseDistrib
    *
    *  @return string
    */

    public function fGetReleaseDistrib( ){
        return 'Not Available';
    }// /fGetReleaseDistrib()




    /*
    *  GET fGetNumCPUs
    *
    *  @return integer
    */

    public function fGetNumCPUs( ){
        return 1; // Not available for Windows
    }// /fGetNumCPUs()

    



    /*
    *  GET TotalFreeHd Space in bytes
    *
    *  @return int $TotalFreeHD
    */

    public function fGetTotalFreeHD( ){
        $UnitPath   = substr($_SERVER['DOCUMENT_ROOT'], 0, 2);
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
    *  GET MemResources
    *
    *  @return array $items
    */

    public function fGetMemResources( ){
        @exec( 'systeminfo', $output );

		foreach ( $output as $value ) {
			if ( preg_match( '|Total Physical Memory\:([^$]+)|', $value, $m ) ) {
				$MemTotal = trim( $m[1] );
				$MemTotal = str_replace('.', '', $MemTotal);
				$MemTotal = str_replace(' MB', '', $MemTotal);
				$MemTotal = (int)$MemTotal;
				$MemTotal *= 1024; // Mb 2 kb
				$MemTotal = (string)$MemTotal;
				} else if ( preg_match( '|Available Physical Memory\:([^$]+)|', $value, $m ) ) {
				$MemFree = trim( $m[1] );
				$MemFree = str_replace('.', '', $MemFree);
				$MemFree = str_replace(' MB', '', $MemFree);
				$MemFree = (int)$MemFree;
				$MemFree *= 1024; // Mb 2 kb
				$MemFree = (string)$MemFree;
				}
			} // /Foreach
        $MemResources = [['MemTotal']=> $MemTotal, ['MemFree']=> $MemFree, ['MemAvailable']=> ($MemTotal-$MemFree) ];

        return $MemResources;
    }// /fGetMemResources()




    /*
    *  GET Uptime String
    *
    *  @return string $uptime
    */

    public function fGetUptime( ){
        $uptime = '';

        @exec( 'systeminfo', $output );

		foreach ( $output as $value ) {
			if ( preg_match( '|System Boot Time\:([^$]+)|', $value, $m ) ) {
				$uptime = 'Uptime From '.trim($m[1]);
				}
			} // /Foreach

        return $uptime;
    }// /fGetUptime()




    /*
    *  GET OS Version
    *
    *  @return string $OsVersion
    */

    public function fGetOSVersion( ){
        $OSName = '';

        @exec( 'systeminfo', $output );

		foreach ( $output as $value ) {
			if ( preg_match( '|OS Name\:([^$]+)|', $value, $m ) ) {
				$OSName = trim( $m[1] );
				}
			} // /Foreach

        return $OSName;
    }// /fGetOSVersion()
} // /fGetSystemResourcesWindows()
?>