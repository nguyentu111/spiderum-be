
export function slideToggle(cardSelector, btnSelector) {
    $(btnSelector).on('click', () => {
        $(cardSelector).slideToggle();
    });
    $(cardSelector).hide();
}
