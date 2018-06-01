$(document).ready(function() {
    var stickyNav = document.querySelector('.js-sticky-nav');

    if (document.querySelector('.csspositionsticky')) {
        stickyNav.classList.remove('js-sticky-nav');
    } else {
        Stickyfill.addOne(stickyNav);
    }
});