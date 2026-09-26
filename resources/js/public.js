document.addEventListener('click', (event) => {
    const toggle = event.target.closest('[data-menu-toggle]');
    if (! toggle) {
        return;
    }
    const panel = document.getElementById(toggle.getAttribute('aria-controls'));
    if (! panel) {
        return;
    }
    panel.classList.toggle('hidden');
    toggle.setAttribute('aria-expanded', String(! panel.classList.contains('hidden')));
});
