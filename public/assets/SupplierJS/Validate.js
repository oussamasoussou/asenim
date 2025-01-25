document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('supplier-form');
    const logoInput = document.getElementById('logo');
    const allowedTypes = ['image/png', 'image/svg+xml'];

    if (form) {
        const isLogoRequired = form.getAttribute('data-logo-required') === 'true';

        const previewImage = document.getElementById('preview-image');
        const logoMessage = document.getElementById('logo-message');

        // Check if there is already an existing logo (for the edit form)
        const isExistingLogo = previewImage.src && !previewImage.classList.contains('d-none');

        if (logoInput) {
            // Update logo preview and validate on file change
            logoInput.addEventListener('change', function (e) {
                const file = e.target.files[0];

                if (file) {
                    if (allowedTypes.includes(file.type)) {
                        const reader = new FileReader();
                        reader.onload = function (event) {
                            previewImage.src = event.target.result;
                            previewImage.classList.remove('d-none');
                            logoMessage.textContent = 'Logo sélectionné';
                        };
                        reader.readAsDataURL(file);
                        document.querySelector('#logoError').textContent = ''; // Clear previous error
                    } else {
                        // Invalid file type
                        previewImage.src = '';
                        previewImage.classList.add('d-none');
                        logoMessage.textContent = 'Aucun logo sélectionné pour le moment';
                        document.querySelector('#logoError').textContent =
                            'Le logo doit être un fichier PNG ou SVG.';
                    }
                } else {
                    // No file selected, show the previous logo if it exists
                    if (isExistingLogo) {
                        previewImage.classList.remove('d-none');
                        logoMessage.textContent = 'Logo actuel';
                    } else {
                        previewImage.classList.add('d-none');
                        logoMessage.textContent = 'Aucun logo sélectionné pour le moment';
                        document.querySelector('#logoError').textContent = ''; // Clear the error
                    }
                }
            });
        } else {
            console.error('Logo input element with ID "logo" not found.');
        }

        // Real-time validation for logo and other fields
        const validateForm = () => {
            let isValid = true;

            // Clear previous errors
            document.querySelector('#nameError').textContent = '';
            document.querySelector('#logoError').textContent = '';
            document.querySelector('#energyTypeError').textContent = '';

            const formData = new FormData(form);
            const name = formData.get('name').trim();
            const logo = logoInput.files[0]; // Get the file directly
            const energyType = formData.get('energy_type');

            // Validate name
            if (!name) {
                isValid = false;
                document.querySelector('#nameError').textContent = 'Le nom est obligatoire.';
            }

            // Validate logo
            if (isLogoRequired || !logo) {
                // If the logo is required but not selected or the size is 0
                // Also check if the current image preview is empty (no image at all)
                if (!isExistingLogo) {
                    isValid = false;
                    document.querySelector('#logoError').textContent = 'Le logo est obligatoire.';
                }
            } else if (logo && !allowedTypes.includes(logo.type)) {
                // If a file is selected but it doesn't match allowed types (PNG or SVG)
                isValid = false;
                document.querySelector('#logoError').textContent =
                    'Le logo doit être un fichier PNG ou SVG.';
            }

            // Validate energy type
            if (!energyType) {
                isValid = false;
                document.querySelector('#energyTypeError').textContent =
                    "Le type d'énergie est obligatoire.";
            }

            return isValid;
        };

        // Handle real-time validation for fields
        form.addEventListener('input', function () {
            validateForm(); // Trigger validation on any input field change
        });

        // Form submission with validation
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            let isValid = validateForm(); // Perform validation

            // If the form is valid, submit via AJAX
            if (isValid) {
                const formData = new FormData(form);

                fetch(form.action, {
                    method: form.method,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = data.redirect;
                        } else if (data.errors) {
                            if (data.errors.name) {
                                document.querySelector('#nameError').textContent = data.errors.name[0];
                            }
                            if (data.errors.logo) {
                                document.querySelector('#logoError').textContent = data.errors.logo[0];
                            }
                            if (data.errors.energy_type) {
                                document.querySelector('#energyTypeError').textContent = data.errors.energy_type[0];
                            }
                        }
                    })
                    .catch(error => console.error('Erreur:', error));
            }
        });
    } else {
        console.error('Form with ID "supplier-form" not found.');
    }
});
