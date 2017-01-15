<?php
//---------------------------------------------------------------------
// Data by MIME type
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

class mime {

	protected $mime;
	protected $extensions;
	protected $sources;

	//-------------------------------------------------
	// Init
	//
	// @param mime
	// @return true
	public function __construct($mime='') {
		$this->mime = null;
		$this->extensions = null;
		$this->sources = null;

		if (false !== $mime = \blobmimes\base::get_mime($mime)) {
			$this->mime = $mime['mime'];
			$this->extensions = $mime['ext'];
			$this->sources = $mime['source'];

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
		return !is_null($this->mime);
	}

	//-------------------------------------------------
	// Get Mime
	//
	// @param n/a
	// @return mime/false
	public function get_mime() {
		return $this->is_valid() ? $this->mime : false;
	}

	//-------------------------------------------------
	// Get Extensions
	//
	// @param n/a
	// @return extensions/false
	public function get_extensions() {
		return $this->is_valid() ? $this->extensions : false;
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
			'mime'=>$this->mime,
			'extensions'=>$this->extensions,
			'sources'=>$this->sources
		);
		return $this->is_valid() ? $out : false;
	}

	//-------------------------------------------------
	// Has Extension?
	//
	// @param extension
	// @return true/false
	public function has_extension($ext='') {
		$ext = trim(\blobmimes\sanitize::strtolower($ext));
		$ext = ltrim($ext, '.*');
		return $this->is_valid() && in_array($ext, $this->extensions);
	}
}

?>