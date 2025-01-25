    document.addEventListener('DOMContentLoaded', () => {
    const supplierTableBody = document.getElementById('supplierTableBody');
    const supplierTable = document.getElementById('supplierTable');
    const searchInput = document.getElementById('searchInput');
    const perPageSelect = document.getElementById('perPageSelect');
    const toggleSuppliers = document.getElementById('toggleSuppliers');
    const pageNumbersContainer = document.getElementById('pageNumbers');

    const supplierListTitle = document.getElementById('supplierListTitle');
    const toggleText = document.getElementById('toggleText');
    const toggleIcon = document.getElementById('toggleIcon');

    // Pagination elements
    const firstPageButton = document.getElementById('firstPage');
    const previousPageButton = document.getElementById('previousPage');
    const nextPageButton = document.getElementById('nextPage');
    const lastPageButton = document.getElementById('lastPage');

    // Modal configuration
    const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
    const confirmationModalLabel = document.getElementById('confirmationModalLabel');
    const confirmationModalBody = document.getElementById('confirmationModalBody');
    const confirmActionButton = document.getElementById('confirmActionButton');
    let currentAction = null;

    const showModal = (title, message, action) => {
        confirmationModalLabel.textContent = title;
        confirmationModalBody.textContent = message;
        currentAction = action;
        confirmationModal.show();
    };

    confirmActionButton.addEventListener('click', () => {
        if (currentAction) {
            currentAction(); // Execute the current action
            confirmationModal.hide();
        }
    });

    let currentPage = 1;
    let perPage = perPageSelect.value || 10;
    let totalPages = 10; // Set a default value for totalPages (should be passed from backend)

    let searchQuery = searchInput.value || '';
    let showDeleted = toggleSuppliers.dataset.showDeleted === 'false';
    let sortField = 'name'; // Default sorting field
    let sortDirection = 'asc'; // Default sorting direction

    // Function to update sorting icons
    const updateSortingIcons = () => {
        const headers = supplierTable.querySelectorAll('th');
        headers.forEach(header => {
            const icon = header.querySelector('.bx');
            if (icon) {
                // Reset the icon to default
                icon.classList.remove('bx-sort-alt');
                icon.classList.add('bx-sort');
                
                // If the header is the sorted field, update to sort-alt
                if (header.dataset.sortField === sortField) {
                    icon.classList.add('bx-sort-alt');
                }
            }
        });
    };

    // Handle sorting click
    const handleSort = (field) => {
        if (sortField === field) {
            // If clicking the same column, toggle direction
            sortDirection = (sortDirection === 'asc') ? 'desc' : 'asc';
        } else {
            // Set new column and default to ascending order
            sortField = field;
            sortDirection = 'asc';
        }
        fetchSuppliers();
        updateSortingIcons(); // Update icons after sorting
    };

    const updatePagination = () => {
        // Clear the page numbers container before adding new ones
        pageNumbersContainer.innerHTML = '';

        // Disable or enable buttons based on the current page
        firstPageButton.querySelector('button').disabled = currentPage === 1;
        previousPageButton.querySelector('button').disabled = currentPage === 1;
        nextPageButton.querySelector('button').disabled = currentPage === totalPages;
        lastPageButton.querySelector('button').disabled = currentPage === totalPages;

        // Generate page number buttons
        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('li');
            pageButton.classList.add('page-item');
            if (i === currentPage) {
                pageButton.classList.add('active');
            }
            pageButton.innerHTML = `<button class="page-link" type="button">${i}</button>`;

            // Add event listener to change the page when a number is clicked
            pageButton.querySelector('button').addEventListener('click', () => {
                currentPage = i;
                fetchSuppliers(); // Refetch suppliers for the new page
            });

            pageNumbersContainer.appendChild(pageButton);
        }
    };

    // Handle previous and next buttons
    previousPageButton.querySelector('button').addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            fetchSuppliers();
        }
    });

    nextPageButton.querySelector('button').addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++;
            fetchSuppliers();
        }
    });

    // Handle perPage change
    perPageSelect.addEventListener('change', () => {
        perPage = perPageSelect.value;
        currentPage = 1; // Reset to page 1 when perPage is changed
        fetchSuppliers();
    });

    // Handle search input change
    searchInput.addEventListener('input', () => {
        searchQuery = searchInput.value;
        currentPage = 1; // Reset to page 1 when search query changes
        fetchSuppliers();
    });

    const fetchSuppliers = () => {
        const fetchUrl = window.fetchSuppliersUrl;  // Use the global variable
        const params = {
            page: currentPage,
            perPage: perPage,
            search: searchQuery,
            show_deleted: showDeleted,
            sort_field: sortField,
            sort_direction: sortDirection
        };

        fetch(`${fetchUrl}?${new URLSearchParams(params).toString()}`, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            // Update table rows
            supplierTableBody.innerHTML = data.suppliers.map(supplier => ` 
                <tr>
                    <td>${supplier.name}</td>
                    <td>${supplier.energy_type}</td>
                    <td>${supplier.logo ? `<img src="${window.assetBaseUrl}/${supplier.logo}" style="width: 50px;">` : 'Pas de logo'}</td>
                    <td>
                        <a href="/suppliers/${supplier.id}/edit" class="btn btn-outline-primary">Modifier</a>
                        ${supplier.deleted_at 
                        ? `<button class="btn btn-outline-success" onclick="confirmRestore(${supplier.id})">Désarchiver</button>` 
                        : `<button class="btn btn-outline-danger" onclick="confirmArchive(${supplier.id})">Archiver</button>`}
                    </td>
                </tr>
            `).join('');
            totalPages = data.pagination.totalPages; // Get totalPages from response
            updatePagination(); // Update pagination controls

        })
        .catch(error => console.error('Error fetching suppliers:', error));
    };
    

    const archiveSupplier = (supplierId) => {
        const params = {
            page: currentPage,
            perPage: perPage,
            search: searchQuery,
            show_deleted: showDeleted
        };

        fetch(`/suppliers/${supplierId}?${new URLSearchParams(params)}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message || 'Fournisseur archivé avec succès.', 'success');
                fetchSuppliers();
            } else {
                showNotification('Erreur lors de l\'archivage.', 'danger');
            }
        })
        .catch(error => console.error('Erreur:', error));
    };

    const restoreSupplier = (supplierId) => {
        const params = {
            page: currentPage,
            perPage: perPage,
            search: searchQuery,
            show_deleted: showDeleted
        };

        fetch(`/suppliers/${supplierId}/restore?${new URLSearchParams(params)}`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message || 'Fournisseur désarchivé avec succès.', 'success');
                fetchSuppliers();
            } else {
                showNotification('Erreur lors du désarchivage.', 'danger');
            }
        })
        .catch(error => console.error('Erreur:', error));
    };

    const showNotification = (message, type = 'success') => {
        let notificationContainer = document.getElementById('notificationContainer');
        if (!notificationContainer) {
            notificationContainer = document.createElement('div');
            notificationContainer.id = 'notificationContainer';
            notificationContainer.className = 'alert alert-dismissible fade show';
            notificationContainer.role = 'alert';
            notificationContainer.innerHTML = `
                <span id="notificationMessage"></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            document.body.appendChild(notificationContainer);
        }

        const notificationMessage = document.getElementById('notificationMessage');
        if (notificationMessage) {
            notificationMessage.textContent = message;
        }
        notificationContainer.className = `alert alert-${type} alert-dismissible fade show`;

        setTimeout(() => {
            notificationContainer.classList.add('d-none');
        }, 5000);
    };

    toggleSuppliers.addEventListener('click', (e) => {
        e.preventDefault();
        currentPage = 1; // Reset to the first page
        search = ''; // Clear the search query
        searchInput.value = '';
        searchQuery = '';

        showDeleted = !showDeleted;
        toggleSuppliers.dataset.showDeleted = showDeleted ? 'true' : 'false';
        toggleText.textContent = showDeleted ? 'Désarchivés' : 'Archivés';
        toggleIcon.className = showDeleted ? 'tf-icons bx bx-archive-out' : 'tf-icons bx bx-archive-in';
        fetchSuppliers();
    });

    searchInput.addEventListener('input', () => {
        searchQuery = searchInput.value;
        fetchSuppliers();
    });

    perPageSelect.addEventListener('change', () => {
        perPage = perPageSelect.value;
        fetchSuppliers();
    });

    // Add sorting functionality to headers
    const headers = supplierTable.querySelectorAll('th[data-sort-field]');
    headers.forEach(header => {
        header.addEventListener('click', () => {
            const field = header.dataset.sortField;
            handleSort(field); // Handle sort by the field
        });
    });

    window.confirmArchive = (supplierId) => {
        showModal(
            'Confirmation d\'archivage',
            'Êtes-vous sûr de vouloir archiver ce fournisseur ?',
            () => archiveSupplier(supplierId)
        );
    };

    window.confirmRestore = (supplierId) => {
        showModal(
            'Confirmation de désarchivage',
            'Êtes-vous sûr de vouloir désarchiver ce fournisseur ?',
            () => restoreSupplier(supplierId)
        );
    };

    fetchSuppliers();
    updateSortingIcons(); // Ensure icons are updated on load
});
