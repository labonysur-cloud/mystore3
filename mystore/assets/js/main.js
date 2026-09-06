document.addEventListener('DOMContentLoaded', function () {
    const mainCategorySelect = document.getElementById('main_category');
    const subcategorySelect = document.getElementById('subcategory');

    if (mainCategorySelect) {
        mainCategorySelect.addEventListener('change', function () {
            const parentId = this.value;

            if (!parentId) {
                if (subcategorySelect) {
                    subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';
                    subcategorySelect.style.display = 'none';
                }
                return;
            }

            fetch(`get_subcategories.php?parent_id=${parentId}`)
                .then(response => response.json())
                .then(data => {
                    if (subcategorySelect) {
                        subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';
                        data.forEach(subcat => {
                            const option = document.createElement('option');
                            option.value = subcat.id;
                            option.textContent = subcat.name;
                            subcategorySelect.appendChild(option);
                        });
                        subcategorySelect.style.display = 'inline-block';
                    }
                })
                .catch(error => {
                    console.error('Error fetching subcategories:', error);
                });
        });
    }

    // Dark mode toggle
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    const moonIcon = document.getElementById('moon-icon');
    const sunIcon = document.getElementById('sun-icon');

    if (darkModeToggle) {
        // Initialize based on saved preference or default
        const savedMode = localStorage.getItem('darkMode');
        if (savedMode === 'enabled') {
            document.body.classList.add('dark-mode');
            moonIcon.style.display = 'none';
            sunIcon.style.display = 'inline';
        } else {
            moonIcon.style.display = 'inline';
            sunIcon.style.display = 'none';
        }

        darkModeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            if (isDark) {
                moonIcon.style.display = 'none';
                sunIcon.style.display = 'inline';
                localStorage.setItem('darkMode', 'enabled');
            } else {
                moonIcon.style.display = 'inline';
                sunIcon.style.display = 'none';
                localStorage.setItem('darkMode', 'disabled');
            }
        });
    }
});
