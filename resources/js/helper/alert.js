export class AlertColor {
    constructor(textColor, bgColor, borderColor) {
        this.textColor = textColor;
        this.bgColor = bgColor;
        this.borderColor = borderColor;
    }
}

export class Alert {
    constructor(message, type = 'success') {
        this.message = message;
        this.alertColor = alertType[type];
        this.headAlert = $('#head-alert');

        this.applyColor(type);
        this.clickToHideAlert()
    }

    applyColor(type) {
        if (type != 'success') {
            $('#head-alert')
                .css('background-color', this.alertColor.bgColor)
                .css('color', this.alertColor.textColor)
                .css('border-color', this.alertColor.borderColor);
        }
    }

    clickToHideAlert() {
        const closeBtn = $('#close-alert');
        closeBtn.on('click', () => {
            this.hideAlert();
        })
    }

    showAlert() {
        this.headAlert.fadeIn(1000);
        this.headAlert.css('display', 'flex');
    }

    hideAlert() {
        this.headAlert.fadeOut(1000, () => {
            this.headAlert.css('display', 'none');
        });
    }

    addText() {
        $('#head-alert span').text(this.message);
    }

    addHTML() {
        $('#head-alert span').html(this.message);
    }

    actionWithHTML() {
        this.showAlert();
        this.addHTML();
    }

    action() {
        this.showAlert();
        this.addText();
    }
}

