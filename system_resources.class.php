<?php
/** system_resources class include
 *
 * 
 * 
 * @author Rafael Martin Soto
 * @author {@link http://www.inatica.com/ Inatica}
 * @since July 2021
 * @version 1.0
 * @license GNU General Public License v3.0
 */

 require_once( 'resources.class.php' );

/* system_resources class
*/
class system_resources
{
    public $IsWindows;
    public $Resources;
	
    
	/**
	 * On create class, it get itself and set the values of Hardware that it can take from the system
     * Works in GNU/Linux & Windows systems
	 */
    public function __construct( ) {
		$this->IsWindows = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'); // This determine the type of OS Windows/Linux

        if( $this->IsWindows ){
			require_once( 'resources_windows.class.php' );
            $this->Resources = new resources_windows();
        } else {
			require_once( 'resources_linux.class.php' );
            $this->Resources = new resources_linux();
        }
		
	} // /__construct()
} // /system_resources class
?>