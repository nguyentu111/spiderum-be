import { Alert } from './helper/alert.js'
import { validateEmail } from './helper/validate.js';


const sendBtn = $('#send-mail-register-btn');
const emailInput = $('input[type="email"]');
const loading = $('#loading-icon').hide();

$(document)
    .ajaxStart(function () {
        sendBtn.attr('disabled', true)
        sendBtn.css('cursor', 'not-allowed')
        loading.show();
    })
    .ajaxStop(function () {
        sendBtn.attr('disabled', false)
        sendBtn.css('cursor', 'pointer')
        loading.hide();
    });

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
                if (result.statusCode == 200) {
                    const alert = new Alert(result.message, 'success');
                    alert.action();
                    emailInput.val('');
                }
                else {
                    const alert = new Alert(result.message, 'error');
                    alert.action();
                }
            }
        })
    }
})
