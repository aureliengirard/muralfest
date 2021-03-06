<?php
/*
* Tiny Compress Images - WordPress plugin.
* Copyright (C) 2015-2018 Tinify B.V.
*
* This program is free software; you can redistribute it and/or modify it
* under the terms of the GNU General Public License as published by the Free
* Software Foundation; either version 2 of the License, or (at your option)
* any later version.
*
* This program is distributed in the hope that it will be useful, but WITHOUT
* ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
* FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
* more details.
*
* You should have received a copy of the GNU General Public License along
* with this program; if not, write to the Free Software Foundation, Inc., 51
* Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

class Tiny_PHP {
	public static function has_fully_supported_php() {
		return version_compare( PHP_VERSION, '5.3', '>' );
	}

	public static function curl_available() {
		return extension_loaded( 'curl' );
	}

	public static function fopen_available() {
		return ini_get( 'allow_url_fopen' );
	}

	public static function curl_exec_disabled() {
		$disabled_functions = explode( ',', ini_get( 'disable_functions' ) );
		return in_array( 'curl_exec', $disabled_functions );
	}

	public static function client_supported() {
		return 	Tiny_PHP::has_fully_supported_php() &&
						Tiny_PHP::curl_available() &&
						! Tiny_PHP::curl_exec_disabled();
	}
}
