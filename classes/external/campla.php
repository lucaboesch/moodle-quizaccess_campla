<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * External functions for quizaccess_campla.
 *
 * @package    quizaccess_campla
 * @copyright  2024
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace quizaccess_campla\external;

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_single_structure;
use core_external\external_value;

/**
 * External API endpoints.
 */
class campla extends external_api {
    /**
     * No parameters for the JWT token request.
     *
     * @return external_function_parameters
     */
    public static function handle_jwttoken_request_parameters(): external_function_parameters {
        return new external_function_parameters([]);
    }

    /**
     * Handle the token request.
     *
     * We validate the plugin capability there.
     *
     * @return array ['status' => int, 'message' => string]
     */
    public static function handle_jwttoken_request(): array {

        self::validate_parameters(self::handle_jwttoken_request_parameters(), []);
        require_login();

        // Delegate to the existing form static method.
        return \quizaccess_campla\form\sendtocamplaform::handle_jwttoken_request();
    }

    /**
     * Return structure of the token call.
     *
     * @return external_single_structure
     */
    public static function handle_jwttoken_request_returns(): external_single_structure {
        return new external_single_structure([
            'status' => new external_value(PARAM_INT, 'HTTP-like status code'),
            'message' => new external_value(PARAM_TEXT, 'Status message'),
        ]);
    }
}
