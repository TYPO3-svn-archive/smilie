<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2008-2010 Peter Schuster <typo3@peschuster.de>
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

/**
 * Replaces all specific strings in a string with smilie images
 *
 * @author		Peter Schuster <typo3@peschuster.de>
 * @package		TYPO3
 * @subpackage 	smilie
 */
class tx_smilie {

	/**
	 * Smilie array
	 *
	 * @var array
	 */
	public $smilies = array();

	/**
	 * Path to smilie folder
	 *
	 * @var string
	 */
	protected $smiliesPath;

	/**
	 * Initiates class, loads and parses typoscript
	 *
	 * @return	void
	 */
	public function __construct() {

		if(!is_array($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_smilie.'])) {
			if ($GLOBALS['TSFE']->id < 1) $GLOBALS['TSFE']->id = 1;
			$this->conf = $this->loadTS();

		} else {
			$this->conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_smilie.'];
		}
		$this->smiliesPath = str_replace('EXT:smilie/',t3lib_extMgm::siteRelPath('smilie'),$this->conf['smiliePath']);
		$this->smilies = $this->parseSmilieArray($this->conf['smilies.']);
	}

	/**
	 * Fetches typoscript configuration for tx_smilie
	 *
	 * @return	array	typoscript configuration
	 */
	protected function loadTS() {
		$tsParser = t3lib_div::makeInstance('t3lib_tsparser_ext');
		$tsParser->tt_track = 0;
		$tsParser->init();
		$sys_page = t3lib_div::makeInstance('t3lib_pageSelect');
		$rootLine = $sys_page->getRootLine($GLOBALS['TSFE']->id);
		$tsParser->runThroughTemplates($rootLine);
		$tsParser->generateConfig();

		return $tsParser->setup['plugin.']['tx_smilie.'];
	}

	/**
	 * Parses TypoScript-Config-Array and creates an valid smilie array
	 *
	 * @param	array	$data: smilie typoscript configuration array
	 * @return	array	parsed smilie array
	 */
	protected function parseSmilieArray($data) {
		$smilies = array();

		if (is_array($data))
			foreach ($data as $k => $v)
				$smilies[$k] = t3lib_div::trimExplode(' ', $v);

		return $smilies;
	}

	/**
	 * Replaces Smilies with img-HTML-Tag
	 *
	 * @param	string		$content: content
	 * @return	string		HTML
	 */
	public function replaceSmilies($content) {
		foreach ($this->smilies as $path => $smilieArray) {
			foreach ($smilieArray as $smilie) {
				$image = '<img alt="' . $smilie . '" title="' . $smilie
					. '" src="' . $this->smiliesPath . '/' . $path . '.' . $this->conf['fileExt'] . '" />';

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