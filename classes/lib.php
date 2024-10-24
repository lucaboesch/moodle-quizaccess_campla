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
     * Submit Ticket.
     *
     * @param array $formdata The form data.
     * @return boolean on success.
     * /
     * @return bool|int
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function submit_ticket(array $formdata): bool|int {
        if (!self::$caps['cansubmittickets'] || !$formdata) {
            return false;
        }
        global $USER, $DB;
        $record = new stdClass();
        $record->title = $formdata->title;
        $record->mobile = $formdata->mobile;
        $record->email = $formdata->email;
        $record->description = $formdata->description;
        $record->created_by = $USER->id;
        $record->created_at = time();
        $record->updated_at = time();
        $record->updated_by = $USER->id;
        $ticketid = $DB->insert_record('local_tickets', $record, true);
        if ($ticketid) {
            // Save Files.
            $context = context_system::instance();
            file_save_draft_area_files($formdata->attachments, $context->id, 'local_tickets', 'attachment', $ticketid);
            // Notify Managers that a new ticket is submitted.
            self::send_notification($ticketid);
        }
        return $ticketid;
    }
}
