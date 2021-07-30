# system-resources. A class to get the hardware resources

## We can get CPU load, CPU/GPU temperature, free/used memory & Hard disk. Written in PHP

It is a library for get system resources. Some times, as we need to control CPU temperature when doing hard tasks as machine learning (deep learning). When we want to do Multithread programming, it would be convenient to know a priory the number of CPU's we can use. I wrote this library to help not only with the CPU temperature or to get the number of CPU'S, also to get all the rest of system resources. Some systems need to get access to some files to read this values, then, a production web server, perhaps is not the best way to access these resources. It is recommended to use a CLI environment, and if we still cannot access the resources, we may have to run the PHP application with a user with permissions to those resources or use SUDO on GNU / Linux systems. The library has been written to obtain the maximum number of resources available on both GNU / Linux servers and Windows, but access to parts of the system is highly restricted, so it may not work correctly in all methods. An attempt has been made to maintain maximum compatibility with return values on both GNU / Linux and Windows. Windows has some other values that we can access, but in different versions of OS we will get some different results.
 
 # REQUERIMENTS:
 
 - A minimum (minimum, minimum, minimum requeriments is needed). Tested on:
 		
    - Simple Raspberry pi (B +	512MB	700 MHz ARM11) with Raspbian Lite PHP7.3 (i love this gadgets)  :heart_eyes:
 		
    - VirtualBox Ubuntu Server 20.04.2 LTS (Focal Fossa) with PHP7.4.3 
 
 
  # FILES:
 There are 4 basic files:
 
 *system_resources.class.php* -> **Master class**. This file is the main file that you need to include in your code. This file includes inside resources.class.php
 
 *resources.class.php* -> **Standard system resources class**
 
 *resources_linux.class.php* -> **Resources for GNU/Linux systems** class
 
 *resources_windows.class.php* -> **Resources for Windows systems** class
 
 
 # INSTALLATION:
 A lot of easy :smiley:. It is written in PURE PHP. Only need to include the files. Tested on basic PHP installation
 
         require_once( 'system_resources.class.php' );
 
 # BASIC USAGE:
 
 - Create the variable with class system resources:
 
        $sys_res = new system_resources();
 
 
 - Print Total Memory space:

        $MemResources = $sys_res->Resources->fGetMemResources( );
        echo $MemResources['MemTotal'];
 
 
# RESUME OF METHODS:

- **CREATE SYSTEM RESOURCES:**
 
*$sys_res = new system_resources(  );*

Example:

        $sys_res = new system_resources();



- **GET OPERATING SYSTEM WINDOWS OR GNU/LINUX:**
 
 By default, we ask for some string in PHP version. if 'WIN' string is in the string, then assume Operating System is Windows, else we assume is GNU/Linux
 
*$sys_res->IsWindows;*

Example:

        if($sys_res->IsWindows){
		echo 'Windows Server';
	     } else {
		echo 'Linux Server';
	     }



- **GET A VALUE FROM BYTES TO NEXT MEASURE (B, KB, MB, GB, TB, PB):**
 
This extra method helps to get results more human readable. It returns a value in Bytes, Kbytes, Mbytes, Gbytes, Tbytes, Pbytes. You pass to the method the value in integer Bytes.
 
*$sys_res->convert( INT );*

Example:

        $TotalHdBytes = $sys_res->Resources->fGetTotalHD();
	    $TotalHd = $sys_res->convert( $TotalHdBytes ); // return 2tb, for example



- **GET CPU LOAD AVERAGE ( Only GNU/Linux ):**

  Get the CPU Average load over the last 1, 5 and 15 minutes, respectively

*$sys_res->Resources->fgetCPUAvgLoad(){*

Example:

        $loadavg =  $sys_res->Resources->fgetCPUAvgLoad()
        $Cpu1minute     = $loadavg[0];
        $Cpu5minutes    = $loadavg[1];
        $Cpu15minutes   = $loadavg[2];
	
	
- **GET CPU LOAD ACTUALLY:**

The system needs to calculate the difference between CPU load in a periode of 1 second. Then this method takes 1 second to execute. Returns a percentage of usage

*$sys_res->Resources->fgetCPULoad();*

Example:

        echo $sys_res->Resources->fgetCPULoad().'% of CPU usage';



- **GET MEM RESOURCES:**

Get the memory available, used, free, .... In linux you have a lot of other values. See resources_linux.class.php to see all of possibilities

*$MemResources = $sys_res->Resources->fGetMemResources();*



Example:

        $MemResources = $sys_res->Resources->fGetMemResources();
        echo 'Total Mem: '.$MermResources['MemTotal'].PHP_EOL;
        echo 'Free Mem: '.$MermResources['MemFree'].PHP_EOL;
        echo 'Available Mem: '.$MermResources['MemAvailable'].PHP_EOL;



- **GET HARD DISK CAPACITY:**

Get total HD Capacity (in bytes)

*$sys_res->Resources->fGetTotalHD();*

Example:

        echo $sys_res->Resources->fGetTotalHD().' bytes';
        
        
- **GET FREE HARD DISK CAPACITY:**

Get Free HD Capacity (in bytes)

*$sys_res->Resources->fGetFreelHD();*

Example:

        echo $sys_res->Resources->fGetFreelHD().' bytes';
        
        
- **GET USED HARD DISK CAPACITY:**

Get used HD Capacity (in bytes)

*$sys_res->Resources->fGetUsedHD();*

Example:

        echo $sys_res->Resources->fGetUsedHD().' bytes';



- **GET UPTIME:**

Get a string uptime.

*$sys_res->Resources->fGetUptime(  );*

Example:

        echo $sys_res->Resources->fGetUptime(  );



- **GET CPU TEMPERATURE IN ºC ( Only GNU/Linux. Don't work on Virtual environments ):**

Get CPU temperature in ºC.

*$sys_res->Resources->fGetCPUTemperature(  );*

Example:

        echo 'CPU Temperature: '.$sys_res->Resources->fGetCPUTemperature(  ).'ºC';



- **GET GPU TEMPERATURE IN ºC ( Only GNU/Linux ):**

Get GPU temperature in ºC.

*$sys_res->Resources->fGetGPUTemperature(  );*

Example:

        echo 'GPU Temperature: '.$sys_res->Resources->fGetGPUTemperature(  ).'ºC';



- **GET OS VERSION:**

Get OS version.

*$sys_res->Resources->fGetOSVersion(  );*

Example:

        echo 'OS Version: '.$sys_res->Resources->fGetOSVersion(  );



- **GET HARDWARE VERSION ( Only GNU/Linux ):**

Get hardware version.

*$sys_res->Resources->fGetHWVersion(  );*

Example:

        echo 'HW Version: '.$sys_res->Resources->fGetHWVersion(  );



- **GET RELEASE DISTRIBUTION VERSION ( Only GNU/Linux ):**

Get Release Distribution.

*$sys_res->Resources->fGetReleaseDistrib(  );*

Example:

        echo 'Release Distribution: '.$sys_res->Resources->fGetReleaseDistrib(  );



- **GET NUMBER OF CPU'S ( Only GNU/Linux ):**

Get Number of CPU'S.

*$sys_res->Resources->fGetNumCPUs(  );*

Example:

        echo 'Number of CPU's: '.$sys_res->Resources->fGetNumCPUs(  );
 
 
 
 # FUTURE PLANS
 
 **1) GET SINGLE CPU TEMPERATURE**
 
 We can get the CPU temperature of a single CPU. Next version will have the option to get the Average temperature of all CPU's or the total CPU temperature of a single CPU.
 
 **2) CREATE EMPTY METHODS ON WINDOWS CLASS FOR COMPATIBILITY WITH GNU/LINUX METHODS**
 
 Most of functions used in GNU/Linux systems is not defined in Windows class. Next step will be create it with null return or something else. In this way, if we call a GNU/Linux function that does not exist in Windows, the system will not fail.


 
 **Of course. You can use it freely :vulcan_salute::alien:**
 
 By Rafa.
 
 
 @author Rafael Martin Soto
 
 @author {@link http://www.inatica.com/ Inatica}
 
 @blog {@link https://rafamartin10.blogspot.com/ Rafael Martin's Blog}
 
 @since July 2021
 
 @version 1.0.0
 
 @license GNU General Public License v3.0
