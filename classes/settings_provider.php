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
 * Class for providing quiz settings, to make setting up quiz form manageable.
 *
 * @package    quizaccess_campla
 * @author     Luca Bösch <luca.boesch@bfh.ch>
 * @copyright  2024 BFH Bern University of Applied Sciences
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace quizaccess_campla;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/question/editlib.php');

/**
 * Helper class for providing quiz settings, to make setting up quiz form manageable.
 *
 * @copyright  2024 BFH Bern University of Applied Sciences
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class settings_provider {

    /**
     * Insert form element.
     *
     * @param \mod_quiz_mod_form $quizform the quiz settings form that is being built.
     * @param \MoodleQuickForm $mform the wrapped MoodleQuickForm.
     * @param \HTML_QuickForm_element $element Element to insert.
     * @param string $before Insert element before.
     */
    protected static function insert_element(\mod_quiz_mod_form $quizform,
        \MoodleQuickForm $mform, \HTML_QuickForm_element $element, $before = 'security') {
        $mform->insertElementBefore($element, $before);
    }

    /**
     * Remove element from the form.
     *
     * @param \mod_quiz_mod_form $quizform the quiz settings form that is being built.
     * @param \MoodleQuickForm $mform the wrapped MoodleQuickForm.
     * @param string $elementname Element name.
     */
    protected static function remove_element(\mod_quiz_mod_form $quizform, \MoodleQuickForm $mform, string $elementname) {
        if ($mform->elementExists($elementname)) {
            $mform->removeElement($elementname);
            $mform->setDefault($elementname, null);
        }
    }

    /**
     * Add setting fields.
     *
     * @param \mod_quiz_mod_form $quizform the quiz settings form that is being built.
     * @param \MoodleQuickForm $mform the wrapped MoodleQuickForm.
     */
    public static function add_campla_settings_fields(\mod_quiz_mod_form $quizform, \MoodleQuickForm $mform) {
        if (self::can_configure_campla($quizform->get_context())) {
            self::add_campla_header_element($quizform, $mform);
            self::add_campla_button($quizform, $mform);
        }
    }

    /**
     * Add CAMPLA header element to the form.
     *
     * @param \mod_quiz_mod_form $quizform the quiz settings form that is being built.
     * @param \MoodleQuickForm $mform the wrapped MoodleQuickForm.
     */
    protected static function add_campla_header_element(\mod_quiz_mod_form $quizform, \MoodleQuickForm $mform) {
        global  $OUTPUT;

        $element = $mform->createElement('header', 'campla', get_string('campla', 'quizaccess_campla'));
        self::insert_element($quizform, $mform, $element);
    }

    /**
     * Add "Generate CAMPLA configuration" button.
     *
     * @param \mod_quiz_mod_form $quizform the quiz settings form that is being built.
     * @param \MoodleQuickForm $mform the wrapped MoodleQuickForm.
     */
    protected static function add_campla_button(\mod_quiz_mod_form $quizform, \MoodleQuickForm $mform) {
        $generatecamplabutton = \html_writer::tag('button', get_string('generatecamplaconfiguration', 'quizaccess_campla'),
            ['class' => 'btn btn-secondary',
            'data-action' => 'opencamplasubmitquizform',
            'onclick' => 'event.preventDefault();']
            );
        $element = $mform->createElement(
            'static',
            'static',
            '',
            $generatecamplabutton,
        );

        self::insert_element($quizform, $mform, $element);
    }

    /**
     * Returns the localized quiz name.
     *
     * @param int $cmid The course module ID.
     * @return string The localized quiz name.
     */
    public static function get_campla_quizname(int $cmid): string {
        if ($cmid === 0) {
            return '';
        }
        [$quiz, ] = get_module_from_cmid($cmid);
        return format_string($quiz->name, true, ['context' => \context_module::instance($cmid)]);
    }

    /**
     * Returns the quiz URL.
     *
     * @param int $cmid The course module ID.
     * @return string The quiz url.
     */
    public static function get_campla_quizurl(int $cmid): string {
        if ($cmid === 0) {
            return '';
        }
        return new \moodle_url('/mod/quiz/view.php', ['cmid' => $cmid]);
    }

    /**
     * Returns the quiz start time.
     *
     * @param int $cmid The course module ID.
     * @return int The quiz timeopen value.
     */
    public static function get_campla_timeopen(int $cmid): int {
        if ($cmid === 0) {
            return 0;
        }
        [$quiz, ] = get_module_from_cmid($cmid);
        return $quiz->timeopen;
    }

    /**
     * Returns the quiz end time.
     *
     * @param int $cmid The course module ID.
     * @return int The quiz timeclose value.
     */
    public static function get_campla_timeclose(int $cmid): int {
        if ($cmid === 0) {
            return 0;
        }
        [$quiz, ] = get_module_from_cmid($cmid);
        return $quiz->timeclose;
    }

    /**
     * Returns the localized course name.
     *
     * @param int $cmid The course module ID.
     * @return string The localized course name.
     */
    public static function get_campla_coursename(int $cmid): string {
        if ($cmid === 0) {
            return '';
        }
        [$quiz, ] = get_module_from_cmid($cmid);
        $course = get_course($quiz->course);
        return format_string($course->fullname, true, ['context' => \context_module::instance($cmid)]);
    }

    /**
     * Returns the course participants with students role.
     *
     * @param int $cmid The course module ID.
     * @return array The course's students list.
     */
    public static function get_campla_coursestudents(int $cmid): array {
        global $DB;
        [$quiz, ] = get_module_from_cmid($cmid);
        $course = get_course($quiz->course);
        $coursecontext = \context_course::instance($course->id);
        $studentrole = $DB->get_record('role', ['shortname' => 'student']);
        return get_enrolled_users($coursecontext, 'moodle/role:student', 0, 'u.*', null, 0, 0, true, $studentrole->id);
    }

    /**
     * Check if the current user can configure CAMPLA.
     *
     * @param \context $context Context to check access in.
     * @return bool
     */
    public static function can_configure_campla(\context $context): bool {
        return has_capability('quizaccess/campla:canusecampla', $context);
    }
}
