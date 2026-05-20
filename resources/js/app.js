document.addEventListener('alpine:init', () => {
    Alpine.store('theme', {
        init() {
            this.dark = localStorage.getItem('theme') === 'dark' ||
                (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches);
        },
        get dark() {
            return this._dark;
        },
        set dark(value) {
            this._dark = value;
            if (value) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },
        toggle() {
            this.dark = !this.dark;
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
        },
    });
});
