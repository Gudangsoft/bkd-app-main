import './bootstrap';

import focus from '@alpinejs/focus';

// Livewire 3 ships its own Alpine instance (via @livewireScripts) and calls
// Alpine.start() itself, so plugins are registered through this hook instead
// of importing/starting a second Alpine instance here.
document.addEventListener('alpine:init', () => {
    Alpine.plugin(focus);

    // Drives the payment create-form fee preview. Replaces the old
    // select2 + jQuery $.ajax handlers wired to the same
    // /payment-assessor-{one,two}/find/{aid}/{did} endpoints.
    Alpine.data('paymentFeeCalculator', (initialDosenId = null) => ({
        dosenId: initialDosenId,
        assessorOneId: '',
        assessorTwoId: '',
        serdosStatus: '',
        assessorOneStatus: '',
        assessorTwoStatus: '',
        amountOne: 0,
        amountTwo: 0,

        get total() {
            return this.amountOne + this.amountTwo;
        },

        formatRupiah(value) {
            return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
        },

        firstSelected(el) {
            return Array.from(el.selectedOptions).map((o) => o.value)[0] || '';
        },

        onDosenChange(el) {
            this.dosenId = this.firstSelected(el) || null;
        },

        async onAssessorOneChange(el) {
            this.assessorOneId = this.firstSelected(el);
            if (!this.assessorOneId || !this.dosenId) return;

            const res = await fetch(`/payment-assessor-one/find/${this.assessorOneId}/${this.dosenId}`);
            const data = await res.json();
            this.serdosStatus = data.serdos_status;
            this.assessorOneStatus = data.assessor_one_status;
            this.amountOne = Number(data.amount_assessor_one);
        },

        async onAssessorTwoChange(el) {
            this.assessorTwoId = this.firstSelected(el);
            if (!this.assessorTwoId || !this.dosenId) return;

            const res = await fetch(`/payment-assessor-two/find/${this.assessorTwoId}/${this.dosenId}`);
            const data = await res.json();
            this.assessorTwoStatus = data.assessor_two_status;
            this.amountTwo = Number(data.amount_assessor_two);
        },
    }));

    // Toggles the "Status Asesor" vs "Status Dosen" field depending on the
    // selected role, on the user create/edit forms. Replaces jQuery show/hide.
    Alpine.data('userRoleFields', (initialRole = '') => ({
        role: initialRole,
    }));

    // Populates the "Pilih Dosen" select once an assessor is chosen on the
    // assignment-letter create form. Replaces jQuery + $.ajax against the
    // same /assignment-letters/{assessorId} endpoint.
    Alpine.data('assignmentLetterForm', () => ({
        dosenOptions: [],

        async onAssessorChange(el) {
            const assessorId = el.value;
            this.dosenOptions = [];
            if (!assessorId) return;

            const res = await fetch(`/assignment-letters/${assessorId}`);
            const data = await res.json();
            this.dosenOptions = data.dosen;
        },
    }));

    // Live-previews a chosen image file before upload. Replaces a jQuery
    // FileReader handler used on the settings/app-logo picker.
    Alpine.data('imagePreview', () => ({
        onFileChange(el) {
            const file = el.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = () => {
                this.$refs.preview.src = reader.result;
            };
            reader.readAsDataURL(file);
        },
    }));

    // Auto-advancing image slider for the home-page ads carousel.
    Alpine.data('adSlider', (count = 0, intervalMs = 5000) => ({
        index: 0,
        count,
        timer: null,

        init() {
            if (this.count > 1) {
                this.timer = setInterval(() => this.next(), intervalMs);
            }
        },

        next() {
            this.index = (this.index + 1) % this.count;
        },

        prev() {
            this.index = (this.index - 1 + this.count) % this.count;
        },

        goTo(i) {
            this.index = i;
        },
    }));
});
