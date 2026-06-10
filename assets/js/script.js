document.addEventListener("DOMContentLoaded", function () {

    const fieldType = document.getElementById("fieldType");
    const optionsBox = document.getElementById("optionsBox");
    const previewBtn = document.getElementById("previewFieldBtn");
    const previewContainer = document.getElementById("previewContainer");

   if (fieldType && optionsBox) {

    function toggleOptions() {

        const value = fieldType.value;

        if (
            value === "dropdown" ||
            value === "radio" ||
            value === "checkbox"
        ) {
            optionsBox.style.display = "block";
        } else {
            optionsBox.style.display = "none";
        }
    }

    toggleOptions();

    fieldType.addEventListener("change", toggleOptions);
}

if (previewBtn && previewContainer) {

    previewBtn.addEventListener("click", function () {

        let type = fieldType.value;

        let label = document.getElementById("label").value.trim();

        if (label === '') {
            label = type;
        }

        let placeholder = document.getElementById("placeholder").value;

        previewContainer.innerHTML += `
            <div class="preview-field">
                <label>${label}</label>
                <input type="${type}" placeholder="${placeholder}">
            </div>
        `;
    });
}

    // Sidebar dropdown

    document.querySelectorAll('.dropdown-toggle').forEach(item => {

        item.addEventListener('click', function(e){

            e.preventDefault();

            this.parentElement.classList.toggle('active');

        });

    });

});