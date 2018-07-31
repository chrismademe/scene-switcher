document.addEventListener('DOMContentLoaded', function() {
    var nav, navToggle, servicesToggle, services;

    // Get Nav elements
    nav = document.getElementById('nav');
    navToggle = document.getElementById('nav-toggle');

    // Toggle navigation
    navToggle.addEventListener('click', function(event) {
        event.preventDefault();
        var open, close;

        // Get open/close icons
        open = navToggle.querySelector('.open');
        close = navToggle.querySelector('.close');

        // Toggle classes on the nav
        nav.classList.toggle('offscreen-l');
        nav.classList.toggle('left-0');

        // Toggle icon classes
        open.classList.toggle('dn');
        close.classList.toggle('dn');

    });

    // Get Services elements
    services = document.getElementById('services-popout');
    servicesToggle = nav.querySelector('[data-key="services"]');

    // Toggle services navigation
    servicesToggle.addEventListener('click', function(event) {
        event.preventDefault();

        services.classList.toggle('db', 'dn');
    });

});
