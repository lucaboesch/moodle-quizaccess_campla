import ModalForm from 'core_form/modalform';
import {add as addToast} from 'core/toast';

const addNotification = (msg, type) => {
    addToast(msg, {type: type});
};

export const modalForm = (linkSelector, formClass, title, args = {...args, hidebuttons: args.hidebuttons ?? 1}) => {
    document.querySelector(linkSelector).addEventListener('click', (e) => {
        e.preventDefault();
        const form = new ModalForm({
            formClass,
            args: args,
            modalConfig: {title: title},
            returnFocus: e.currentTarget
        });
        form.addEventListener(form.events.FORM_SUBMITTED, (e) => {
            // Comes from process_dynamic_submission() in submitticketform.php
            const response = e.detail;
            const type = response.status == 200 ? 'success' : 'danger';
            addNotification(response.message, type);
        });
        form.addEventListener(form.events.ERROR, (e) => addNotification('Oopsie - ' + e.detail.message));
        form.show();
    });

};
