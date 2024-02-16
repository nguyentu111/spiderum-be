import { slideToggle } from './helper/slide-toggle';
import { Alert } from './helper/alert';

slideToggle('#card', '#toggle-more-info')

if (errorMessage) {
    const errorAlert = new Alert(errorMessage, 'error');
    console.log(errorAlert.alertColor);
    errorAlert.action();
}

