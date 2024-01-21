import { Alert } from './helper/alert.js'
import { validateEmail } from './helper/validate.js';

const alert = new Alert('Gửi email', 'haha');

const sendBtn = $('#send-mail-register-btn');
const emailInput = $('input[type="email"]');

sendBtn.on('click', (event) => {
    if (validateEmail(emailInput.val())) {
        event.preventDefault()
    }
    // sendBtn.post('/mail-send-to-register', {
    //     email: 1
    // }, () => {
    //     console.log(email.emailInput)
    // })
})
