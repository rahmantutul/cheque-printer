import './bootstrap';
import Alpine from 'alpinejs';

Alpine.data('appShell', () => ({
    sidebarOpen: false,
    menuOpen: false,
    desktop: window.matchMedia('(min-width: 1024px)').matches,

    init() {
        this.$watch('sidebarOpen', (open) => {
            document.documentElement.classList.toggle('overflow-hidden', open && !this.desktop);
            if (open) {
                this.$nextTick(() => this.$refs.sidebarClose.focus());
            }
        });
    },

    resize() {
        this.desktop = window.matchMedia('(min-width: 1024px)').matches;
        if (this.desktop) {
            this.sidebarOpen = false;
            document.documentElement.classList.remove('overflow-hidden');
        }
    },

    closeSidebar() {
        this.sidebarOpen = false;
        this.$nextTick(() => this.$refs.sidebarToggle.focus());
    },

    trapSidebar(event) {
        if (!this.sidebarOpen || this.desktop || event.key !== 'Tab') return;
        const items = [...this.$refs.sidebar.querySelectorAll('a[href], button:not([disabled])')]
            .filter((item) => item.getClientRects().length > 0);
        const first = items[0];
        const last = items[items.length - 1];
        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    },
}));

Alpine.data('confirmModal', () => ({
    action: null,
    error: '',
    opener: null,

    open(detail) {
        const form = document.getElementById(detail?.action);
        if (!(form instanceof HTMLFormElement)) return;
        if (form.method.toLowerCase() !== 'post') return;
        if (new URL(form.action, window.location.href).origin !== window.location.origin) return;
        this.action = form;
        this.error = '';
        this.opener = document.activeElement;
        this.$refs.dialog.showModal();
        this.$refs.cancel.focus();
    },

    close() {
        this.$refs.dialog.close();
    },

    restoreFocus() {
        this.action = null;
        this.opener?.focus();
    },

    confirm() {
        if (!this.action?.isConnected) {
            this.error = 'The form is no longer available. Close this dialog and try again.';
            return;
        }
        const form = this.action;
        this.close();
        form.requestSubmit();
    },
}));

Alpine.data('amountPreview', (config = {}) => ({
    amount: config.initialAmount || '', templateId: config.templateId || '', words: {}, pending: false, error: null,
    init() { if (this.amount) this.fetchWords(); },
    async fetchWords() { const value = parseFloat(this.amount); if (!value || value <= 0) { this.words = {}; return; } this.pending = true; try { const params = new URLSearchParams({ amount: value, template_id: this.templateId || '' }); const response = await fetch(`${config.templateUrl}?${params}`); if (!response.ok) throw new Error(); this.words = await response.json(); this.error = null; } catch (error) { this.error = 'Could not fetch amount in words.'; } finally { this.pending = false; } },
}));

Alpine.data('chequeForm', (config = {}) => ({
    templates: config.templates || [], bankId: config.initialBankId || '', templateId: config.initialTemplateId || '', amount: config.initialAmount || '', words: {}, pending: false, error: null,
    get filteredTemplates() { return this.bankId ? this.templates.filter((template) => String(template.bank_id) === String(this.bankId)) : []; },
    get hasMemo() { return this.templates.find((template) => String(template.id) === String(this.templateId))?.has_memo || false; },
    init() { this.onBankChange(true); if (this.amount) this.fetchWords(); },
    onBankChange(silent = false) { if (!this.filteredTemplates.some((template) => String(template.id) === String(this.templateId))) this.templateId = ''; if (!silent && this.amount) this.fetchWords(); },
    onTemplateChange() { if (this.amount) this.fetchWords(); },
    async fetchWords() { const value = parseFloat(this.amount); if (!value || value <= 0) { this.words = {}; return; } this.pending = true; try { const response = await fetch(`${config.amountUrl}?${new URLSearchParams({ amount: value, template_id: this.templateId })}`); if (!response.ok) throw new Error(); this.words = await response.json(); this.error = null; } catch (error) { this.error = 'Could not fetch amount in words.'; } finally { this.pending = false; } },
}));

window.Alpine = Alpine;
Alpine.start();
