<?php
/**
 * Class MimesTests
 *
 * @package blobfolio/mimes
 */

/**
 * Test media-mimes.php functions.
 */
class MimesTests extends WP_UnitTestCase {

	/**
	 * Filter Aliases
	 *
	 * @param array|bool $mimes MIMEs.
	 * @param string $ext Extension.
	 * @return array|bool MIMEs.
	 */
	function filter_wp_get_mime_aliases( $mimes, $ext ) {
		if ( $ext === 'jpg' ) {
			if ( ! is_array( $mimes ) ) {
				$mimes = array();
			}
			$mimes[] = 'image/foobar';
		}

		return $mimes;
	}

	/**
	 * Filter Alias Checking
	 *
	 * @param bool $match Status.
	 * @param string $ext Extension.
	 * @param string $mime MIME.
	 * @return bool True or false.
	 */
	function filter_wp_check_mime_alias( $match, $ext, $mime ) {
		if ( $ext === 'jpg' && $mime === 'image/foobar' ) {
			return true;
		}

		return $match;
	}

	/**
	 * Get Aliases
	 */
	function test_wp_get_mime_aliases() {
		$thing = wp_get_mime_aliases( 'jpg' );
		$this->assertNotEquals( false, $thing );
		$this->assertEquals( true, in_array( 'image/jpeg', $thing, true ) );

		$thing = wp_get_mime_aliases( 'jpeg' );
		$this->assertNotEquals( false, $thing );

		$thing = wp_get_mime_aliases( 'JPG' );
		$this->assertNotEquals( false, $thing );

		$thing = wp_get_mime_aliases( '.JPEG' );
		$this->assertNotEquals( false, $thing );

		$thing = wp_get_mime_aliases( 'jpggg' );
		$this->assertEquals( false, $thing );

		// Test aliases filter.
		$thing = wp_get_mime_aliases( 'jpg' );
		$this->assertEquals( false, in_array( 'image/foobar', $thing, true ) );

		add_filter ( 'wp_get_mime_aliases', array( $this, 'filter_wp_get_mime_aliases' ), 10, 2 );
		$thing = wp_get_mime_aliases( 'jpg' );
		$this->assertEquals( true, in_array( 'image/foobar', $thing, true ) );
		remove_filter ( 'wp_get_mime_aliases', array( $this, 'filter_wp_get_mime_aliases' ), 10, 2 );
	}

	/**
	 * Check Aliases
	 */
	function test_wp_check_mime_alias() {
		$thing = wp_check_mime_alias( 'jpg', 'image/jpeg' );
		$this->assertEquals( true, $thing );

		$thing = wp_check_mime_alias( 'jpg', 'image/pjpeg' );
		$this->assertEquals( true, $thing );

		$thing = wp_check_mime_alias( 'jpg', 'image/x-jpeg' );
		$this->assertEquals( true, $thing );

		$thing = wp_check_mime_alias( 'jpg', 'image/gif' );
		$this->assertEquals( false, $thing );

		// Test aliases filter.
		$thing = wp_check_mime_alias( 'jpg', 'image/foobar' );
		$this->assertEquals( false, $thing );

		add_filter ( 'wp_get_mime_aliases', array( $this, 'filter_wp_get_mime_aliases' ), 10, 2 );
		$thing = wp_check_mime_alias( 'jpg', 'image/foobar' );
		$this->assertEquals( true, $thing );
		remove_filter ( 'wp_get_mime_aliases', array( $this, 'filter_wp_get_mime_aliases' ), 10, 2 );

		// Test check filter.
		$thing = wp_check_mime_alias( 'jpg', 'image/foobar' );
		$this->assertEquals( false, $thing );

		add_filter ( 'wp_check_mime_alias', array( $this, 'filter_wp_check_mime_alias' ), 10, 3 );
		$thing = wp_check_mime_alias( 'jpg', 'image/foobar' );
		$this->assertEquals( true, $thing );
		remove_filter ( 'wp_check_mime_alias', array( $this, 'filter_wp_check_mime_alias' ), 10, 3 );
	}
}
