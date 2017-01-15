<?php
//---------------------------------------------------------------------
// Data by file
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

class file {

	protected $file;

	//-------------------------------------------------
	// Init
	//
	// @param path
	// @return true
	public function __construct($path='') {
		$this->file = null;

		$path = \blobmimes\sanitize::path($path);
		if (!\blobmimes\base::strlen($path)) {
			return false;
		}

		//basic file info
		$this->file = pathinfo($path);
		$this->file['path'] = $path;
		$this->file['mime'] = \blobmimes\base::MIME_DEFAULT;
		$this->file['suggested'] = array();

		//let's see what we can find based on the named extension
		$extension = new \blobmimes\extension($this->file['extension']);
		if ($extension->is_valid()) {
			$data = $extension->get();
			$this->file['mime'] = $data['mime'];
		}

		//try to read the file with magic!
		try {
			//find the real path, if possible
			if (false !== ($path = realpath($path))) {
				$this->file['path'] = $path;
				$this->file['dirname'] = dirname($path);
			}

			//lookup magic mime, if possible
			if (false !== $path && function_exists('finfo_file') && is_file($path)) {
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$magic_mime = finfo_file($finfo, $path);

				if ($magic_mime &&
					$magic_mime !== \blobmimes\base::MIME_DEFAULT &&
					!$extension->has_mime($magic_mime, true)
				) {
					//if we have an alternative magic mime and it is legit,
					//it should override what we derived from the name
					$mime = new \blobmimes\mime($magic_mime);
					if ($mime->is_valid()) {
						$this->file['mime'] = $mime->get_mime();
						$extensions = $mime->get_extensions();
						foreach ($extensions as $ext) {
							$this->file['suggested'][] = $this->file['filename'] . '.' . $ext;
						}
					}
				}
			}
		} catch (\Throwable $e) {
			return true;
		} catch (\Exception $e) {
			return true;
		}

		return true;
	}

	//-------------------------------------------------
	// Is Valid?
	//
	// @param content match?
	// @return true/false
	public function is_valid($strict=false) {
		return !is_null($this->file) && (!$strict || !$this->has_incorrect_name());
	}

	//-------------------------------------------------
	// Has Corrections?
	//
	// @param n/a
	// @return true/false
	public function has_incorrect_name() {
		return isset($this->file['suggested']) && count($this->file['suggested']);
	}

	//-------------------------------------------------
	// Get File
	//
	// @param n/a
	// @return file/false
	public function get_file() {
		return $this->is_valid() ? $this->file : false;
	}

	//-------------------------------------------------
	// Get it all
	//
	// @param n/a
	// @return all/false
	public function get() {
		return $this->get_file();
	}
}

?>