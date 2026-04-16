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
 * Manage the CAMPLA access rule modal form.
 *
 * @module     quizaccess_campla
 * @author     Luca Bösch <luca.boesch@bfh.ch>
 * @copyright  2025 BFH Bern University of Applied Sciences
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import ModalForm from 'core_form/modalform';
import Ajax from 'core/ajax';
import {add as addToast} from 'core/toast';
import {getString} from 'core/str';
import Notification from 'core/notification';

const addNotification = (msg, type) => {
    addToast(msg, {type: type});
};

/**
 * Open a dynamic modal form and, upon open, fetch/store the CAMPLA AWT token via AJAX.
 *
 * @param {string} linkSelector CSS selector for the trigger element
 * @param {string} formClass Fully-qualified PHP form class name (e.g. 'quizaccess_campla\\form\\sendtocamplaform')
 * @param {string} title Modal title
 * @param {Object} args Arguments passed to the form (e.g., {cmid, hidebuttons})
 */


/** ---- Toast z-index fallback: inject CSS once per page ---- */
const ensureToastZIndexCSS = (() => {
    let injected = false;
    const STYLE_ID = 'campla-toast-zindex-css';
    return function ensureToastZIndexCSS() {
        if (injected || document.getElementById(STYLE_ID)) {
            injected = true;
            return;
        }
        const style = document.createElement('style');
        style.id = STYLE_ID;
        style.textContent = `
            .toast-wrapper, .toast-container {
                z-index: 2000 !important; /* above modal/backdrop */
            }
            .toast { pointer-events: auto; }
        `;
        document.head.appendChild(style);
        injected = true;
    };
})();

// Inject immediately so it’s ready before any toast is shown.
ensureToastZIndexCSS();

export const modalForm = (linkSelector, formClass, title, args = {...args, hidebuttons: args.hidebuttons ?? 1}) => {
    // Ensure default: hidebuttons = 1 unless explicitly disabled.
    args.hidebuttons = (args.hidebuttons ?? 1);

    const link = document.querySelector(linkSelector);
    if (!link) {
        return;
    }

    link.addEventListener('click', async(e) => {
        e.preventDefault();

        // REST API call when the modal is opened.

        const form = new ModalForm({
            formClass,
            args: args,
            modalConfig: {title: title},
            saveButtonText: getString('sendtocampla', 'quizaccess_campla'),
            returnFocus: e.currentTarget
        });

        form.addEventListener(form.events.FORM_SUBMITTED, (e) => {
            // REST API call when form is submitted.
            // Comes from process_dynamic_submission() in sendtocamplaform.php
            const response = e.detail;
            const type = response.status == 200 ? 'success' : 'danger';
            addNotification(response.message, type);
        });
        form.addEventListener(form.events.ERROR, (e) => addNotification('Oopsie - ' + e.detail.message));

        // Open the modal first for a snappy UX.
        form.show();

        // Then call the token endpoint.
        try {
            // Comes from handle_jwttoken_request() in sendtocamplaform.php
            const [request] = await Ajax.call([{
                methodname: 'quizaccess_campla_handle_jwttoken_request',
                args: {cmid: args.cmid}
            }]);

            const resp = await request;

            // Decide toast type based on returned status
            let toastType;
            if (resp.status < 300) {
                toastType = 'success';
            } else if (resp.status === 401 || resp.status === 412) {
                toastType = 'warning';
            } else {
                toastType = 'danger';
            }

            // ✅ Show the toast immediately after the request succeeds (resolved)
            // If you want it *only* for success, wrap this in `if (resp.status === 200) { ... }`
            addNotification(resp.message, toastType);

        } catch (ex) {
            Notification.exception(ex);
        }
    });

};
