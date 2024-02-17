import { slideToggle } from './helper/slide-toggle';
import { Alert } from './helper/alert';

slideToggle('#card', '#toggle-more-info')
const registerBtn = $('#register-btn');

if (errorMessage) {
    const errorAlert = new Alert(errorMessage, 'error');
    console.log(errorAlert.alertColor);
    errorAlert.action();
    registerBtn
        .css('pointer-events', 'none')
}

if (errors) {
    const errorAlert = new Alert(errors.join('<br/>'), 'error');
    console.log(errorAlert.alertColor);
    errorAlert.actionWithHTML();
}
