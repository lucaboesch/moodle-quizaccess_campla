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
 * Lib class for quizaccess_campla.
 *
 * @package    quizaccess_campla
 * @author     Luca Bösch <luca.boesch@bfh.ch>
 * @copyright  2024 BFH Bern University of Applied Sciences
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace quizaccess_campla;

use function Symfony\Component\Translation\t;

/**
 * CAMPLA quiz access rule library code.
 *
 * Lib that contiains functions
 * for crud operations and some
 * other stuff
 *
 * @package    quizaccess_campla
 * @author     Luca Bösch <luca.boesch@bfh.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class lib {

    /**
     * User Capabilities.
     *
     * @var bool[]
     */
    public static $caps;

    /**
     * Init function.
     *
     * should be called before using
     * this class
     *
     * @return \stdClass on success.
     */
    public static function init() {
        global $USER;
        $context = \context_system::instance();
        self::$caps['canusecampla'] = has_capability('quizaccess/campla:canusecampla', $context);
    }

    /**
     * Send to CAMPLA.
     *
     * @param \stdClass $formdata The form data in a URI encoded param string
     * @return boolean on success.
     * /
     * @return bool|int
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function sendtocampla(\stdClass $formdata): bool|int {
        global $USER;

        if (!self::$caps['canusecampla'] || !$formdata) {
            return false;
        }

        [$quiz, ] = get_module_from_cmid($formdata->cmid);
        $course = get_course($quiz->course);
        $coursecontext = \context_course::instance($course->id);

        $studentids = get_enrolled_users($coursecontext, 'mod/quiz:attempt', 0, 'u.id,u.email',
            'u.email', 0, 0, false);

        $students = [];

        foreach ($studentids as $userid) {
            $user = \core_user::get_user($userid->id);
            $students[] = [
                'email' => $user->email,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'fullname' => fullname($user),
            ];
        }
        $record = new \stdClass();
        $record->quizname = $formdata->quizname;
        $record->coursename = $formdata->coursename;
        $record->quizurl = $formdata->quizstarturl;
        $record->quizopens = $formdata->quizopensunixtime;
        $record->quizcloses = $formdata->quizclosesunixtime;
        $record->students = $formdata->students;
        $record->submitteremail = $USER->email;
        $record->created_at = time();
        $record->students = $students;

        // Sending the data to CAMPLA.
        $url = get_config('quizaccess_campla', 'camplabasisurl');
        $secret = get_config('quizaccess_campla', 'secret');

        // Initiate cURL object with URL.
        $ch = curl_init($url);

        curl_setopt( $ch, CURLOPT_POSTFIELDS, trim(json_encode($record), '[]'));
        curl_setopt( $ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);

        // Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        // Send request.
        $result = curl_exec($ch);
        if ($result === false) {
            die(curl_error($ch));
            return false;
        }
        curl_close($ch);

        return true;
    }
}
