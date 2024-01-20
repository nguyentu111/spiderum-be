export class AlertColor {
    constructor(textColor, bgColor, borderColor) {
        this.textColor = textColor;
        this.bgColor = bgColor;
        this.borderColor = borderColor;
    }
}

export class Alert {
    constructor(message, id, type) {
        this.message = message;
        this.id = id;
        this.alertColor = alertType[type];
        this.headAlert = $('#head-alert');
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

    action() {
        this.showAlert();
        this.addText();
    }
}

