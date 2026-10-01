// Porsche Service Care - Interactive JS Scripts

document.addEventListener('DOMContentLoaded', function () {
    // 1. Multiple image upload preview
    const imageInput = document.getElementById('repairImagesInput');
    const previewContainer = document.getElementById('imagePreviewContainer');

    if (imageInput && previewContainer) {
        imageInput.addEventListener('change', function (e) {
            previewContainer.innerHTML = '';
            const files = Array.from(e.target.files);

            if (files.length === 0) {
                previewContainer.innerHTML = '<div class="col-12 text-muted small">ยังไม่ได้เลือกรูปภาพ</div>';
                return;
            }

            files.forEach((file, index) => {
                if (!file.type.startsWith('image/')) return;

                const reader = new FileReader();
                reader.onload = function (event) {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 col-6 mb-3';
                    col.innerHTML = `
                        <div class="card h-100 shadow-sm border-0 position-relative">
                            <img src="${event.target.result}" class="card-img-top rounded" style="height: 130px; object-fit: cover;" alt="Preview">
                            <div class="card-body p-2 text-center">
                                <span class="badge bg-dark text-truncate d-block" style="max-width: 100%;">${file.name}</span>
                                <small class="text-muted d-block mt-1">${(file.size / 1024).toFixed(1)} KB</small>
                            </div>
                        </div>
                    `;
                    previewContainer.appendChild(col);
                };
                reader.readAsDataURL(file);
            });
        });
    }

    // 2. Auto-fill sample Porsche VIN for easy form testing
    const vinInput = document.getElementById('vinNumberInput');
    const btnFillVin = document.getElementById('btnFillSampleVin');

    if (btnFillVin && vinInput) {
        btnFillVin.addEventListener('click', function () {
            const sampleVins = [
                'WP0ZZZ99ZTS192834',
                'WP0AA2Y15PSA11209',
                'WP1ZZZ9YZPDA45912',
                'WP0AC2A82NSA09182',
                'WP1AA2A17RSA99812'
            ];
            const randomVin = sampleVins[Math.floor(Math.random() * sampleVins.length)];
            vinInput.value = randomVin;
            vinInput.classList.add('is-valid');
        });
    }

    // 3. Service Catalog auto-select price in Technician quotation builder
    const catalogSelect = document.getElementById('catalogSelectPicker');
    if (catalogSelect) {
        catalogSelect.addEventListener('change', function () {
            const selectedOpt = catalogSelect.options[catalogSelect.selectedIndex];
            if (!selectedOpt || !selectedOpt.value) return;

            const nameInput = document.getElementById('item_name_input');
            const codeInput = document.getElementById('part_code_input');
            const priceInput = document.getElementById('unit_price_input');
            const typeInput = document.getElementById('item_type_input');

            if (nameInput) nameInput.value = selectedOpt.dataset.name || '';
            if (codeInput) codeInput.value = selectedOpt.dataset.code || '';
            if (priceInput) priceInput.value = selectedOpt.dataset.price || '0';
            if (typeInput && selectedOpt.dataset.type) typeInput.value = selectedOpt.dataset.type;
        });
    }
});

// Helper confirm dialog using SweetAlert2 if available or fallback to confirm
function confirmAction(message, formId) {
    if (window.Swal) {
        Swal.fire({
            title: 'ยืนยันการทำรายการ?',
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d5001c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'ตกลง',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    } else {
        if (confirm(message)) {
            document.getElementById(formId).submit();
        }
    }
}
