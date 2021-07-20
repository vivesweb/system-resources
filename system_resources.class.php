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
	
	
    /**
     * Returns a given value in bytes at its closest value in b, kb, mb, gb, tb, pb
     * Original from https://www.programmersought.com/article/87232238278/
     */
    public function convert($size){
    	$unit=array('b','kb','mb','gb','tb','pb');
     	return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    } // /convert()
} // /system_resources class
?>
