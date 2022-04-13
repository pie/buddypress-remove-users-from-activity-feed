<?php

/*
Plugin Name: BuddyPress Remove Users From Activity Feed
Description: Prevents specific users from being displayed in the BP activity feed
Version:     0.1
Author:      The team at PIE
Author URI:  http://pie.co.de
License:     GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

/* PIE\BPRemoveUsersFromActivity is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version.

PIE\BPRemoveUsersFromActivity is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with PIE\BPRemoveUsersFromActivity. If not, see https://www.gnu.org/licenses/gpl-3.0.en.html */

namespace PIE\BPRemoveUsersFromActivity;

if ( ! class_exists( 'BP_Remove_Users_From_Activity' ) ) {

  class BP_Remove_Users_From_Activity {

    /**
     * Array of user IDs to exclude from the string
     * @var array
     */
    public $user_ids = array( 1187 );

    public function __construct() {
      add_filter( 'bp_activity_get_user_join_filter', array( $this, 'filter_bp_activity_get_user_join_filter', 20, 4 ) );
    }

    /**
     * Adds a MySQL WHERE clause to exclude specfic User IDs to a string. It is
     * hooked onto the query used to grab BuddyPress activity.
     *
     * @param  string $sql    Concatenated MySQL statement pieces to be query
     *                        results with for legacy query.
     * @param  string $select Final SELECT MySQL statement portion for legacy query.
     * @param  string $from   Final FROM MySQL statement portion for legacy query.
     * @param  string $where  Final WHERE MySQL statement portion for legacy query.
     * @return string            Modified query string
     */
    public function filter_bp_activity_get_user_join_filter( $sql, $select, $from, $where ) {
    	if ( ! bp_is_user() ) {
    		$user_ids_string = implode(',', $this->user_ids );
    		echo '<!-- ' . $sql . '    ' . str_replace( $where, $where . ' AND u.ID NOT IN ('. $user_ids_string .') ', $sql ) . ' -->';
    		$sql = str_replace( $where, $where . ' AND u.ID NOT IN ('. $user_ids_string .') ', $sql );
    	}

    	return $sql;
    }
  }
}
