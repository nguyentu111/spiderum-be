import { Alert } from './helper/alert.js'
import { validateEmail } from './helper/validate.js';

const alert = new Alert('Gửi email', 'haha');

const sendBtn = $('#send-mail-register-btn');
const emailInput = $('input[type="email"]');

sendBtn.on('click', (event) => {
    const email = emailInput.val();
    if (validateEmail(email)) {
        event.preventDefault()
        $.ajax({
            method: 'POST',
            url: '/auth/mail-send-to-register',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            data: {
                email: email
            },
            success: (result) => {
                console.log(result)
            }
        })
    }
})
