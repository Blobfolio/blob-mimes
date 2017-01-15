<?php
//---------------------------------------------------------------------
// Data by file extension
//---------------------------------------------------------------------
// blob-mimes v0.5
// https://github.com/Blobfolio/blob-mimes
//
// REQUIREMENTS:
//   -- PHP 5.3.2
//   -- JSON
//
// OPTIONAL:
//   -- MBSTRING
//   -- FINFO
//   -- WordPress
//
// Copyright © 2017  Blobfolio, LLC  (email: hello@blobfolio.com)
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

namespace blobmimes;

class extension {

	protected $extension;
	protected $mime;
	protected $mimes;
	protected $mime_aliases;
	protected $sources;

	//-------------------------------------------------
	// Init
	//
	// @param ext
	// @return true
	public function __construct($ext='') {
		$this->extension = null;
		$this->mime = null;
		$this->mimes = null;
		$this->mime_aliases = null;
		$this->sources = null;

		if (false !== $ext = \blobmimes\base::get_ext($ext)) {
			$this->extension = $ext['ext'];
			$this->mime = $ext['primary'];
			$this->mimes = $ext['mime'];
			$this->mime_aliases = $ext['alias'];
			$this->sources = $ext['source'];

			sort($this->mime_aliases);
			sort($this->sources);
		}

		return true;
	}

	//-------------------------------------------------
	// Is Valid?
	//
	// @param n/a
	// @return true/false
	public function is_valid() {
		return !is_null($this->extension);
	}

	//-------------------------------------------------
	// Get Extension
	//
	// @param n/a
	// @return ext/false
	public function get_extension() {
		return $this->is_valid() ? $this->extension : false;
	}

	//-------------------------------------------------
	// Get (Primary) Mime
	//
	// @param n/a
	// @return mime/false
	public function get_mime() {
		return $this->is_valid() ? $this->mime : false;
	}

	//-------------------------------------------------
	// Get Mimes
	//
	// @param n/a
	// @return mimes/false
	public function get_mimes() {
		return $this->is_valid() ? $this->mimes : false;
	}

	//-------------------------------------------------
	// Get Sources
	//
	// @param n/a
	// @return sources/false
	public function get_sources() {
		return $this->is_valid() ? $this->sources : false;
	}

	//-------------------------------------------------
	// Get it all
	//
	// @param n/a
	// @return all/false
	public function get() {
		$out = array(
			'extension'=>$this->extension,
			'mime'=>$this->mime,
			'mimes'=>$this->mimes,
			'sources'=>$this->sources
		);
		return $this->is_valid() ? $out : false;
	}

	//-------------------------------------------------
	// Has MIME?
	//
	// @param mime
	// @param loose (match x- variants)
	// @return true/false
	public function has_mime($mime='', $loose=false) {
		if (!$this->is_valid()) {
			return false;
		}

		$mime = \blobmimes\sanitize::strtolower($mime);
		$real = array_map('\blobmimes\sanitize::strtolower', $this->mimes);
		$test = array(
			$mime
		);

		//for loose matching, we will look for both
		//whatever/whatever and whatever/x-whatever
		if ($loose) {
			$parts = explode('/', $mime);
			if (preg_match('/^x\-/', $parts[count($parts) - 1])) {
				$parts[count($parts) - 1] = preg_replace('/^x\-/', '', $parts[count($parts) - 1]);
			}
			else {
				$parts[count($parts) - 1] = 'x-' . $parts[count($parts) - 1];
			}
			$test[] = implode('/', $parts);
		}

		$found = array_intersect($real, $test);
		return count($found) > 0;
	}
}

?>