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
 * Form to send a quiz configuration to CAMPLA
 *
 * @package    quizaccess_campla
 * @author     Luca Bösch <luca.boesch@bfh.ch>
 * @copyright  2024 BFH Bern University of Applied Sciences
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace quizaccess_campla\form;

defined('MOODLE_INTERNAL') || die;

use core_reportbuilder\external\filters\set;
use quizaccess_campla\campla_client;
use quizaccess_campla\settings_provider;

require_once(__DIR__ . '/../../../../../../config.php');
require_once($CFG->libdir . '/filelib.php');

require_login();

/**
 * Form to send a quiz configuration to CAMPLA
 *
 * @package    quizaccess_campla
 * @copyright  2024 BFH Bern University of Applied Sciences
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class sendtocamplaform extends \core_form\dynamic_form {
    /**
     * Returns form context
     *
     * If context depends on the form data, it is available in $this->_ajaxformdata or
     * by calling $this->optional_param()
     *
     * @return \context
     */
    protected function get_context_for_dynamic_submission(): \context {
        $cmid = isset($this->_ajaxformdata['cmid']) ? (int) $this->_ajaxformdata['cmid'] : 0;
        $context = \context_module::instance($cmid);
        return $context;
    }

    /**
     * Check if current user has access to this form, otherwise throw exception
     *
     */
    protected function check_access_for_dynamic_submission(): void {
        $cmid = isset($this->_ajaxformdata['cmid']) ? (int) $this->_ajaxformdata['cmid'] : 0;
        $context = \context_module::instance($cmid);
        require_capability('quizaccess/campla:canusecampla', $context);
    }

    /**
     * Load in existing data as form defaults
     *
     */
    public function set_data_for_dynamic_submission(): void {
        $this->set_data([
            'hidebuttons' => $this->optional_param('hidebuttons', false, PARAM_BOOL),
        ]);
    }

    /**
     * Process the form submission, used if form was submitted via AJAX
     *
     * This method can return scalar values or arrays that can be json-encoded, they will be passed to the caller JS.
     *
     * Submission data can be accessed as: $this->get_data()
     *
     * @return array ['status' => int, 'message'=> string]
     */
    public function process_dynamic_submission(): array {
        $formdata = $this->get_data();
        campla_client::init($formdata);
        $success = campla_client::sendtocampla($formdata);

        if ($success) {
            return [
                'status' => 200,
                'message' => get_string('sendtocamplasuccess', 'quizaccess_campla'),
            ];
        }
        return [
            'status' => 500,
            'message' => get_string('sendtocamplafail', 'quizaccess_campla'),
        ];
    }

    /**
     * Handles the external AJAX call to fetch and store the jwt token before form submission.
     *
     * This is called from JS when the modal opens.
     *
     * @return array
     */
    public static function handle_jwttoken_request(): array {
        global $PAGE;
        $record = new \stdClass();
        $record->applicationId = settings_provider::read_camplaappid();
        $record->secret = settings_provider::read_secret();

        $url = settings_provider::read_camplabasisurl() . '/auth/application/';

        $parts = parse_url($url);

        $hostvalid = true;

        if (!$parts || empty($parts['host'])) {
            $hostvalid = false;
        }

        $host = $parts['host'];
        if ($host !== 'localhost') {
            $records = dns_get_record($host, DNS_A + DNS_AAAA);

            if ($records === false || empty($records)) {
                $hostvalid = false;
            } else {
                $hostvalid = true;
            }
        }

        // Allow localhost. For testing purposes only.
        if ($host === 'localhost') {
            $hostvalid = true;
        }

        if ($hostvalid) {
            // Initiate cURL object with URL.
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_POSTFIELDS, trim(json_encode($record), '[]'));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);

            // Return response instead of printing.
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Send request.
            $curlresult = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($curlresult === false) {
                die(curl_error($ch));
                return false;
            }
            curl_close($ch);

            if ($httpcode === 200) {
                $result = json_decode($curlresult, true);
                if (!empty($result['token'])) {
                    if ($result['token'] !== \quizaccess_campla\token_manager::read_token()) {
                        // The token is different, so we need to save the new one.
                        \quizaccess_campla\token_manager::save_token($result['token']);
                    }
                    return [
                        'status' => 200,
                        'message' => get_string('tokenstored', 'quizaccess_campla'),
                    ];
                } else {
                    return [
                        'status' => 500,
                        'message' => get_string('invalidtokenresponse', 'quizaccess_campla'),
                    ];
                }
            }

            if ($httpcode === 401) {
                return [
                    'status' => 401,
                    'message' => 'The application is not authorized.',
                ];
            }

            if ($httpcode === 412) {
                return [
                    'status' => 412,
                    'message' => 'The login credentials does not meet the validation requirements.',
                ];
            }

            return [
                'status' => 500,
                'message' => 'CAMPLA server error.',
            ];
        } else {
            return [
                'status' => 500,
                'message' => 'No valid CAMPLA URL configured.',
            ];
        }
    }

    /**
     * Returns url to set in $PAGE->set_url() when form is being rendered or submitted via AJAX
     *
     * This is used in the form elements sensitive to the page url.
     *
     * @return \moodle_url
     */
    protected function get_page_url_for_dynamic_submission(): \moodle_url {
        return new \moodle_url('/mod/quiz/accessrule/campla/');
    }

    /**
     * Form definition
     *
     * @return void
     */
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('text', 'quizname', get_string('quizname', 'quizaccess_campla'));
        $mform->setType('quizname', PARAM_NOTAGS);
        $mform->addRule('quizname', get_string('required'), 'required', null, 'client');

        $mform->addElement('text', 'coursename', get_string('coursename', 'quizaccess_campla'));
        $mform->setType('coursename', PARAM_NOTAGS);
        $mform->addRule('coursename', get_string('required'), 'required', null, 'client');

        $mform->addElement('text', 'quizowner', get_string('quizowner', 'quizaccess_campla'));
        $mform->setType('quizowner', PARAM_NOTAGS);
        $mform->addRule('quizowner', get_string('required'), 'required', null, 'client');

        $mform->addElement('text', 'quizurl', get_string('quizurl', 'quizaccess_campla'));
        $mform->setType('quizurl', PARAM_URL);

        $mform->addElement('text', 'quizopens', get_string('quizopens', 'mod_quiz'));
        $mform->setType('quizopens', PARAM_NOTAGS);

        $mform->addElement('text', 'quizcloses', get_string('quizcloses', 'mod_quiz'));
        $mform->setType('quizcloses', PARAM_NOTAGS);

        $securityleveloptions = [
            '1' => get_string('securitylevellernstick', 'quizaccess_campla'),
            '5' => get_string('securitylevelseb', 'quizaccess_campla'),
        ];
        $mform->addElement(
            'select',
            'securitylevel',
            get_string('securitylevel', 'quizaccess_campla'),
            $securityleveloptions,
        );
        $mform->setType('securitylevel', PARAM_NOTAGS);
        $mform->addRule('securitylevel', get_string('required'), 'required', null, 'client');

        $mform->addElement('hidden', 'quizstarturl');
        $mform->setType('quizstarturl', PARAM_URL);

        $mform->addElement('hidden', 'quizopensunixtime');
        $mform->setType('quizopensunixtime', PARAM_INT);

        $mform->addElement('hidden', 'quizclosesunixtime');
        $mform->setType('quizclosesunixtime', PARAM_INT);

        $mform->addElement('hidden', 'cmid');
        $mform->setType('cmid', PARAM_INT);

        $mform->freeze(['quizurl', 'quizopens', 'quizcloses']);

        if (empty($this->_ajaxformdata['hidebuttons'])) {
            $this->add_action_buttons(true, get_string('submitlabel', 'quizaccess_campla'));
        }

        $cmid = isset($this->_ajaxformdata['cmid']) ? (int) $this->_ajaxformdata['cmid'] : 0;
        $quizname = \quizaccess_campla\settings_provider::get_campla_quizname($cmid);
        $coursename = \quizaccess_campla\settings_provider::get_campla_coursename($cmid);
        $quizowner = \quizaccess_campla\settings_provider::get_campla_quizowner();
        $quizurl = new \moodle_url('/mod/quiz/view.php', ['id' => $cmid]);
        if (\quizaccess_campla\settings_provider::get_campla_timeopen_unixtime($cmid) !== 0) {
            $quizopens = \core_date::strftime(
                get_string('strftimerecentfull', 'langconfig'),
                \quizaccess_campla\settings_provider::get_campla_timeopen_unixtime($cmid)
            );
            $quizopensunixtime = \quizaccess_campla\settings_provider::get_campla_timeopen_unixtime($cmid);
        } else {
            $quizopens = get_string('na', 'quizaccess_campla');
            $quizopensunixtime = 0;
        }
        if (\quizaccess_campla\settings_provider::get_campla_timeclose_unixtime($cmid) !== 0) {
            $quizcloses = \core_date::strftime(
                get_string('strftimerecentfull', 'langconfig'),
                \quizaccess_campla\settings_provider::get_campla_timeclose_unixtime($cmid),
            );
            $quizclosesunixtime = \quizaccess_campla\settings_provider::get_campla_timeclose_unixtime($cmid);
        } else {
            $quizcloses = get_string('na', 'quizaccess_campla');
            $quizclosesunixtime = 0;
        }

        $securitylevel = get_config('quizaccess_campla', 'securitylevel') ?? 5;

        $this->set_data([
            'quizname' => $quizname,
            'coursename' => $coursename,
            'quizowner' => $quizowner,
            'quizurl' => $quizurl,
            'quizstarturl' => $quizurl,
            'quizopens' => $quizopens,
            'quizcloses' => $quizcloses,
            'quizopensunixtime' => $quizopensunixtime,
            'quizclosesunixtime' => $quizclosesunixtime,
            'securitylevel' => $securitylevel,
            'cmid' => $cmid,
            'hidebuttons' => $this->optional_param('hidebuttons', false, PARAM_BOOL),
        ]);
    }
}
