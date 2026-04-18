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
 * Strings for the quizaccess_campla plugin.
 *
 * @package    quizaccess_campla
 * @author     Luca Bösch <luca.boesch@bfh.ch>
 * @copyright  2024 BFH Bern University of Applied Sciences
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['applicationunauthorized'] = 'The application is not authorized.';
$string['campla'] = 'CAMPLA';
$string['campla:canusecampla'] = 'Can use CAMPLA for a quiz setup.';
$string['camplaappid'] = 'CAMPLA Application ID';
$string['camplaappid_desc'] = 'The Application ID CAMPLA uses to identify the Moodle instance.';
$string['camplabasisurl'] = 'CAMPLA REST API URL';
$string['camplabasisurl_desc'] = 'URL used to access the CAMPLA API (without trailing / slash).';
$string['camplalink'] = 'After successful sending to CAMPLA and its exam creation, the exam can be accessed by the URL<br/>{$a}.';
$string['camplanotconfigured'] = 'The CAMPLA endpoint is not configured. Please contact the administrator.';
$string['camplasecret'] = 'CAMPLA Application secret';
$string['camplasecret_desc'] = 'The Application secret CAMPLA uses to identify the Moodle instance.';
$string['camplasecuritylevel'] = 'CAMPLA default security level';
$string['camplasecuritylevel_desc'] = 'The security level that should be default when sending an exam to CAMPLA.';
$string['camplaservererror'] = 'CAMPLA server error.';
$string['coursename'] = 'Course name';
$string['generatebuttoninfo'] = 'The "Generate CAMPLA configuration" button is only active if there are a saved quiz start time
("Open the quiz") and quiz end time ("Close the quiz"). Since this form does not entirely save via AJAX, saving must have happened
beforehand.</p><p>Please set the following settings in the section "Timing":</p><ul><li>On "Open the quiz", enable the checkbox
and set a time and date.</li><li>On "Close the quiz", enable the checkbox
and set a time and date.</li></ul><p>Save the changes for them to take effect.</p>';
$string['generatebuttoninfo_past'] = 'The "Generate CAMPLA configuration" button is only active if there are a saved quiz start time
("Open the quiz") and quiz end time ("Close the quiz"). Since this form does not entirely save via AJAX, saving must have happened
beforehand.</p><p>Both this dates have to be in the future.</p><p>In the section "Timing", either the date in "Open the quiz", or both the "Open the quiz" as well as the "Close the quiz"
date are set in the past.</p><p>Please go change them and save the changes for them to take effect, then come back here again.</p>';
$string['generatecamplaconfiguration'] = 'Generate CAMPLA configuration';
$string['invalidtokenresponse'] = 'Invalid response from JWT token request.';
$string['na'] = 'N/A';
$string['newquizinstanceinfo'] = 'The settings in this section will be only visible once the quiz is created.';
$string['novalidcamplaurl'] = 'No valid CAMPLA URL configured.';
$string['pluginname'] = 'CAMPLA exam configuration';
$string['privacy:metadata:quizaccess_campla:email'] = 'The email of the user using CAMPLA with this quiz.';
$string['privacy:metadata:quizaccess_campla:externalpurpose'] = 'This information is sent to the CAMPLA server to set up an exam for a user. No user data is explicitly sent to the CAMPLA server or stored in Moodle LMS by this plugin.';
$string['privacy:metadata:quizaccess_campla:firstname'] = 'The first name of the user using CAMPLA with this quiz.';
$string['privacy:metadata:quizaccess_campla:lastname'] = 'The last name of the user using CAMPLA with this quiz.';
$string['quitpassword'] = 'Quit password';
$string['quizname'] = 'Quiz name';
$string['quizowner'] = 'Quiz owner';
$string['quizurl'] = 'Quiz URL';
$string['securitylevel'] = 'Security level';
$string['securitylevellernstick'] = 'Lernstick';
$string['securitylevelseb'] = 'Safe Exam Browser';
$string['sendtocampla'] = 'Send to CAMPLA';
$string['sendtocamplafail'] = 'Failed sending to CAMPLA';
$string['sendtocamplasuccess'] = 'Sent to CAMPLA Successfully';
$string['settingnotification'] = 'Please note that the following settings relay on a a valid set up CAMPLA (Cloud Assessment
    Management Platform) see <a href="https://campla.ch">https://campla.ch</a> for more information.';
$string['submitlabel'] = 'Send to CAMPLA';
$string['tokenstored'] = 'CAMPLA JWT token stored successfully.';
$string['unknownerror'] = 'Unknown error';
$string['wrongcredentialvalidation'] = 'The login credentials does not meet the validation requirements.';
