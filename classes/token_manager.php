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
 * Class for managing the JWT token.
 *
 * @package    quizaccess_campla
 * @author     Luca Bösch <luca.boesch@bfh.ch>
 * @copyright  2025 BFH Bern University of Applied Sciences
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace quizaccess_campla;

/**
 * Helper class for managing the JWT token.
 *
 * @copyright  2025 BFH Bern University of Applied Sciences
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class token_manager {
    /**
     * Save JWT token.
     *
     * @param string $jwttoken The JWT token.
     */
    public static function save_token($jwttoken) {
        set_config('jwttoken', $jwttoken, "quizaccess_campla");
    }

    /**
     * Read JWT token.
     *
     * @return string
     * @throws \dml_exception
     */
    public static function read_token(): string {
        $jwttoken = get_config('quizaccess_campla', 'jwttoken') ?? "";
        return $jwttoken;
    }
}
