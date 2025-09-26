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
 * Implementaton of the quizaccess_campla plugin.
 *
 * @package    quizaccess_campla
 * @author     Luca Bösch <luca.boesch@bfh.ch>
 * @copyright  2024 BFH Bern University of Applied Sciences
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_quiz\local\access_rule_base;
use quizaccess_campla\settings_provider;

/**
 * A rule adding a form section with a "Generate CAMPLA link" button.
 *
 * @copyright  2024 BFH Bern University of Applied Sciences
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quizaccess_campla extends access_rule_base {
    /**
     * Add any fields that this rule requires to the quiz settings form.
     *
     * @param mod_quiz_mod_form $quizform the quiz settings form that is being built.
     * @param MoodleQuickForm $mform the wrapped MoodleQuickForm.
     */
    public static function add_settings_form_fields(mod_quiz_mod_form $quizform, MoodleQuickForm $mform) {
        global $COURSE, $PAGE;

        $modulecontext = $quizform->get_context();
        $context = context_course::instance($COURSE->id);
        $canusecampla = has_capability('quizaccess/campla:canusecampla', $context);
        $cmid = $modulecontext->instanceid;

        if ($canusecampla) {
            settings_provider::add_campla_settings_fields($quizform, $mform);
            $PAGE->requires->js_call_amd(
                'quizaccess_campla/modalforms',
                'modalForm',
                ['[data-action=opencamplasubmitquizform]',
                    \quizaccess_campla\form\sendtocamplaform::class,
                    get_string('generatecamplaconfiguration', 'quizaccess_campla'),
                    ['hidebuttons' => 1, 'cmid' => $cmid],
                ],
            );
        }
    }
}
