export default (placeholder = '+7 000 000 00 00', startPhone = '+7 ') => ({
    placeholder,
    startPhone,
    phoneNumber: startPhone,
    root: {
        ['x-mask']: '+7 999 999 99 99',
        [':placeholder']() {
            return this.placeholder
        },
        ['@focus']() {
            this.phoneNumber = startPhone;
            this.placeholder = startPhone;
        },
        ['@blur']() {
            if (this.phoneNumber.replace(/\D/g, '').length === 1) {
                this.phoneNumber = '';
            }
            this.placeholder = placeholder;
        }
    }
});
