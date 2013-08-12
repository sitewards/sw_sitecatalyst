<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Sitewards GmbH <mail@sitewards.com>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Plugin 'Sitewards SiteCatalyst' for the 'sw_sitecatalyst' extension.
 *
 * @author	Sitewards GmbH <mail@sitewards.com>
 * @package	TYPO3
 * @subpackage	tx_swsitecatalyst
 */
class tx_swsitecatalyst_pi1 extends tslib_pibase {
	public $prefixId      = 'tx_swsitecatalyst_pi1';		// Same as class name
	public $scriptRelPath = 'pi1/class.tx_swsitecatalyst_pi1.php';	// Path to this script relative to the extension dir.
	public $extKey        = 'sw_sitecatalyst';	// The extension key.
	public $pi_checkCHash = TRUE;

	const I_LENGTH_IMPLODE = 3;
	const I_POS_CHANNEL = 1;

	/**
	 * The main method of the Plugin.
	 *
	 * @param string $sContent The Plugin content
	 * @param array $aConf The Plugin configuration
	 * @return string The content that is displayed on the website
	 */
	public function main($sContent, array $aConf) {
		$this->conf = $aConf;
		$this->pi_setPiVarDefaults();

		$sTemplateContent = $this->cObj->fileResource($aConf['templateFile']);

		$aMarkers = array(
			'###JS_FILE###' => $aConf['jsFile'],
			'###PAGENAME###' => $this->getPagename(),
			'###CHANNEL###' => $this->getChannel(),
			'###HIERARCHY###' => $this->getHierarchy(),
			'###PROP18###' => $this->getProp18(),
			'###LOGINSTATUS###' => $this->getLoginStatus(),
		);

		$sContent .= $this->cObj->substituteMarkerArrayCached($sTemplateContent, null, $aMarkers);

		return $this->pi_wrapInBaseClass($sContent);
	}

	/**
	 * returns the login status, "logged in" or "not logged in"
	 *
	 * @return string
	 */
	protected function getLoginStatus() {
		$aCallable = explode('->', $this->conf['loggedIn.']['userFunc']);
		// also pass some parameters which you could define via typoscript
		$aParams = $this->conf['loggedIn.'];
		unset($aParams['userFunc']);
		return (
			call_user_func_array($aCallable, $aParams) ?
			'logged in' :
			'not logged in'
		);
	}

	/**
	 * implodes the uri
	 *
	 * @param string $sSeparator
	 * @param int $iOffset = 0
	 * @return string
	 */
	private function implodeUri($sSeparator, $iOffset = 0) {
		$aUri = $this->getUri();
		// give me max. 3 levels
		$aUri = array_slice(
			$aUri,
			$iOffset,
			(self::I_LENGTH_IMPLODE - $iOffset)
		);
		return implode($sSeparator, $aUri);
	}

	/**
	 * returns value for prop18, i.e. "foo:bar" for url /de/foo/bar
	 *
	 * @return string
	 */
	protected function getProp18() {
		return $this->implodeUri(':', 1);
	}

	/**
	 * returns value for pagename, i.e. "de:foo:bar" for url /de/foo/bar
	 *
	 * @return string
	 */
	protected function getPagename() {
		return $this->implodeUri(':');
	}

	/**
	 * returns value for channel, i.e. "foo" for url /de/foo/bar
	 *
	 * @return string
	 */
	protected function getChannel() {
		$aUri = $this->getUri();
		return $aUri[self::I_POS_CHANNEL];
	}

	/**
	 * returns value for hierarchy, i.e. "de|foo|bar" for url /de/foo/bar
	 *
	 * @return string
	 */
	protected function getHierarchy() {
		return $this->implodeUri('|');
	}

	/**
	 * returns uri as an array
	 *
	 * @return array
	 */
	private function getUri () {
		$aUri = explode('/', t3lib_div::getIndpEnv('REQUEST_URI'));
		// remove empty values
		$aUri = array_filter(
			$aUri,
			function ($sValue) {
				return (!empty($sValue));
			}
		);
		return $aUri;
	}

	/**
	 * default function to determine if user is logged in
	 *
	 * @return bool
	 */
	public static function isLoggedIn() {
		return ($GLOBALS['TSFE']->fe_user->user['uid'] != 0);
	}
}



if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sw_sitecatalyst/pi1/class.tx_swsitecatalyst_pi1.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sw_sitecatalyst/pi1/class.tx_swsitecatalyst_pi1.php']);
}

?>