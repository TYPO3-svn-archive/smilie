<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2008-2009 Peter Schuster <typo3@peschuster.de>
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
 * class.tx_smilie.php
 *
 * $Id$
 *
 * @author Peter Schuster <typo3@peschuster.de>
 */

require_once(PATH_t3lib.'class.t3lib_page.php');
require_once(PATH_t3lib.'class.t3lib_tstemplate.php');
require_once(PATH_t3lib.'class.t3lib_tsparser_ext.php');


/**
 * Replaces all specific strings in a string with smilie images
 *
 * @author		Peter Schuster <typo3@peschuster.de>
 * @package		TYPO3
 * @subpackage 	smilie
 */
class tx_smilie {
	var $prefixId		= 'tx_smilie';				// Same as class name
	var $scriptRelPath	= 'class.tx_smilie.php';	// Path to this script relative to the extension dir.
	var $extKey			= 'smilie';					// The extension key.
	var $smilies = array();							// Smilie-Array

/**
	* Initiates class
	*
	* @return	void
	*/
	function __construct() {
		$tsParser = t3lib_div::makeInstance('t3lib_tsparser_ext');
		$tsParser->tt_track = 0;
		$tsParser->init();
		$sys_page = t3lib_div::makeInstance('t3lib_pageSelect');
		$rootLine = $sys_page->getRootLine(1);
		$tsParser->runThroughTemplates($rootLine);
		$tsParser->generateConfig();

		$this->conf = $tsParser->setup['plugin.']['tx_smilie.'];

		$this->arrayParser();
	}

	/**
	 * Parses TypoScript-Config-Array and creates and valid smilie array
	 *
	 * @return	void
	 */
	private function arrayParser() {
		$this->smilies = array();
		foreach ($this->conf['smilies.'] as $k => $v) {
			$this->smilies[$k] = t3lib_div::trimExplode(' ', $v);
		}
	}

	/**
	 * Replaces Smilies with img-HTML-Tag
	 *
	 * @param	string		$content: content
	 * @return	string		HTML
	 */
	public function replaceSmilies($content) {
		$smiliesPath = str_replace('EXT:smilie/',t3lib_extMgm::siteRelPath($this->extKey),$this->conf['smiliePath']);

		foreach ($this->smilies as $path => $smilieArray) {
			foreach ($smilieArray as $smilie) {
				$image = '<img alt="'.$smilie.'" title="'.$smilie.'" src="'.$smiliesPath.'/'.$path.'.'.$this->conf['fileExt'].'" />';
				$content = str_ireplace($smilie, $image, $content);
			}
		}
		return $content;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/smilie/class.tx_smilie.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/smilie/class.tx_smilie.php']);
}

?>