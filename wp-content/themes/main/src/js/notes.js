document.addEventListener('DOMContentLoaded', () => {
    const sections = document.querySelectorAll('.section-notes-two');

    if (!sections.length) return;

    function formatRubles(value) {
        const number = Number(value || 0);

        return new Intl.NumberFormat('ru-RU', {
            maximumFractionDigits: 0,
        }).format(number);
    }

    function getCommemorationName(item) {
        return item.commemoration || item['subtype-name'] || item.name || '';
    }

    function getCommemorationDonation(item) {
        return item.donation || item['subtype-donation'] || item.donate || 0;
    }

    function getCommemorationDonationType(item) {
        return item.donation_type || item['donation-type'] || item.donationType || 'per_list';
    }

    function isVisible(element) {
        return !!(
            element &&
            (element.offsetWidth || element.offsetHeight || element.getClientRects().length)
        );
    }

    sections.forEach((section) => {
        const form = section.querySelector('.section-notes-two__form');
        if (!form) return;

        const dropdowns = section.querySelectorAll('.section-notes-two__dropdown');

        const typeInput = form.querySelector('input[name="type"]');
        const commemorationInput = form.querySelector('input[name="commemoration"]');
        const donateInput = form.querySelector('input[name="donate"]');

        const nameInputs = form.querySelectorAll('input[name="names-array[]"]');
        const btn = form.querySelector('.btn-submit');

        const typeTitle = section.querySelector('.section-notes-two__type');
        const commemorationTitle = section.querySelector('.section-notes-two__commemoration');
        const donationValue = section.querySelector('#donation-value');

        const personalInputs = section.querySelectorAll('.personal__input');

        if (!btn) return;

        personalInputs.forEach((input) => {
            input.removeAttribute('required');
        });

        function getCurrentTypeData() {
            if (!window.notes || !Array.isArray(window.notes)) return null;

            const currentType = typeInput ? typeInput.value : '';

            return window.notes.find((note) => note.type === currentType) || null;
        }

        function getCurrentCommemorationData() {
            const typeData = getCurrentTypeData();
            if (!typeData) return null;

            const commemorations = typeData.commemorations || typeData['subtype-repeater'] || [];
            const currentCommemoration = commemorationInput ? commemorationInput.value : '';

            return commemorations.find((item) => {
                return getCommemorationName(item) === currentCommemoration;
            }) || null;
        }

        function updateDonation() {
            const commemorationData = getCurrentCommemorationData();

            if (!commemorationData || !donateInput || !donationValue) return;

            const baseDonation = Number(getCommemorationDonation(commemorationData));
            const donationType = getCommemorationDonationType(commemorationData);

            let total = baseDonation;

            if (donationType === 'per_name') {
                const filledCount = Array.from(nameInputs).filter((input) => {
                    return input.value.trim() !== '';
                }).length;

                total = baseDonation * filledCount;
            }

            donateInput.value = total;
            donationValue.textContent = `${formatRubles(total)} рублей`;
            donationValue.dataset.type = donationType;
        }

        function hasAgreement() {
            const visibleCheckboxes = Array.from(personalInputs).filter((input) => {
                return isVisible(input.closest('.personal')) || isVisible(input);
            });

            if (!visibleCheckboxes.length) {
                return true;
            }

            return visibleCheckboxes.some((input) => input.checked);
        }

        function hasFilledName() {
            return Array.from(nameInputs).some((input) => {
                return input.value.trim() !== '';
            });
        }

        function updateButtonState() {
            if (hasFilledName() && hasAgreement()) {
                btn.removeAttribute('disabled');
            } else {
                btn.setAttribute('disabled', 'true');
            }
        }

        function closeDropdowns() {
            dropdowns.forEach((dropdown) => {
                const body = dropdown.querySelector('.section-notes-two__dropdown-body');
                if (body) body.classList.remove('active');
            });
        }

        function renderCommemorations(typeName) {
            if (!window.notes || !Array.isArray(window.notes)) return;

            const typeData = window.notes.find((note) => note.type === typeName);
            if (!typeData) return;

            const commemorations = typeData.commemorations || typeData['subtype-repeater'] || [];
            const secondDropdown = dropdowns[1];

            if (!secondDropdown || !commemorations.length) return;

            const content = secondDropdown.querySelector('.section-notes-two__dropdown-content');
            const head = secondDropdown.querySelector('.section-notes-two__dropdown-head');
            const headName = secondDropdown.querySelector('.section-notes-two__dropdown-name');

            if (!content || !head || !headName || !commemorationInput) return;

            content.innerHTML = '';

            commemorations.forEach((item) => {
                const name = getCommemorationName(item);

                const div = document.createElement('div');
                div.className = 'section-notes-two__dropdown-item';
                div.dataset.value = name;
                div.dataset.text = name;
                div.dataset.parent = typeName;

                div.innerHTML = `
                    <p class="p2-400 section-notes-two__dropdown-text">${name}</p>
                `;

                content.appendChild(div);
            });

            const firstCommemoration = getCommemorationName(commemorations[0]);

            head.dataset.commemoration = firstCommemoration;
            headName.textContent = firstCommemoration;
            commemorationInput.value = firstCommemoration;

            if (commemorationTitle) {
                commemorationTitle.textContent = firstCommemoration;
            }

            updateDonation();
        }

        dropdowns.forEach((dropdown, index) => {
            const head = dropdown.querySelector('.section-notes-two__dropdown-head');
            const body = dropdown.querySelector('.section-notes-two__dropdown-body');
            const headName = dropdown.querySelector('.section-notes-two__dropdown-name');

            if (!head || !body || !headName) return;

            head.addEventListener('click', (event) => {
                event.stopPropagation();

                dropdowns.forEach((item) => {
                    if (item !== dropdown) {
                        const itemBody = item.querySelector('.section-notes-two__dropdown-body');
                        if (itemBody) itemBody.classList.remove('active');
                    }
                });

                body.classList.toggle('active');
            });

            dropdown.addEventListener('click', (event) => {
                const item = event.target.closest('.section-notes-two__dropdown-item');
                if (!item) return;

                event.stopPropagation();

                const text = item.dataset.text || item.dataset.value || '';

                headName.textContent = text;

                if (index === 0) {
                    if (typeInput) typeInput.value = text;

                    head.dataset.type = text;

                    if (typeTitle) {
                        typeTitle.textContent = text;

                        if (item.dataset.color) {
                            typeTitle.style.color = item.dataset.color;
                        }
                    }

                    renderCommemorations(text);
                }

                if (index === 1) {
                    if (commemorationInput) commemorationInput.value = text;

                    head.dataset.commemoration = text;

                    if (commemorationTitle) {
                        commemorationTitle.textContent = text;
                    }

                    updateDonation();
                }

                body.classList.remove('active');
                updateButtonState();
            });
        });

        document.addEventListener('click', closeDropdowns);

        nameInputs.forEach((input) => {
            input.addEventListener('input', () => {
                updateDonation();
                updateButtonState();
            });
        });

        personalInputs.forEach((input) => {
            input.addEventListener('change', updateButtonState);
        });

        form.addEventListener('htmx:configRequest', (event) => {
            updateDonation();

            event.detail.parameters.action = 'notes';
        });

        form.addEventListener('htmx:beforeRequest', () => {
            updateDonation();

            btn.classList.add('loading');
            btn.setAttribute('disabled', 'true');
        });

        form.addEventListener('htmx:afterRequest', () => {
            btn.classList.remove('loading');
            updateButtonState();
        });

        form.addEventListener('htmx:responseError', (event) => {
            console.error('HTMX response error:', event.detail);

            btn.classList.remove('loading');
            updateButtonState();
        });

        updateDonation();
        updateButtonState();
    });
});