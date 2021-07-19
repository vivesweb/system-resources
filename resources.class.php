<?php
/** resources class include
 *
 * 
 * 
 * @author Rafael Martin Soto
 * @author {@link http://www.inatica.com/ Inatica}
 * @since July 2021
 * @version 1.0
 * @license GNU General Public License v3.0
 */

/* resources class
*/
class resources
{
    public $TotalHD;
    public $OSVersion;
    public $HWVersion;
    public $ReleaseDistrib;
    public $NumCPUs;
	
    
	/**
	 * On create class, it get itself and set the values of Hardware that it can take from the system
     * Works in GNU/Linux & Windows systems
	 */
    public function __construct( ) {
        // Load Fixed Values of the System Resources

        $this->TotalHD          = $this->fGetTotalHD();
        $this->OSVersion        = $this->fGetOSVersion();
        $this->HWVersion        = $this->fGetHWVersion();
        $this->ReleaseDistrib   = $this->fGetReleaseDistrib();
        $this->NumCPUs          = $this->fGetNumCPUs();		
	} // /__construct()

} // /resources class
?>