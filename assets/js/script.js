document.addEventListener("DOMContentLoaded", function () {

    const fieldType = document.getElementById("fieldType");
    const optionsBox = document.getElementById("optionsBox");
    const previewContainer = document.getElementById("previewContainer");

    const labelInput = document.getElementById("label");
    const placeholderInput = document.getElementById("placeholder");
    const requiredField = document.getElementById("requiredField");
    const optionsTextarea = document.querySelector(
        'textarea[name="options"]'
    );

    /* Show/Hide Options Box */

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

        fieldType.addEventListener(
            "change",
            toggleOptions
        );
    }

    /* Live Preview */

    function renderPreview() {

        if (!previewContainer || !fieldType) {
            return;
        }

        const type = fieldType.value;

        const label =
            labelInput?.value.trim() ||
            "Field Label";

        const placeholder =
            placeholderInput?.value ||
            "";

        const required =
            requiredField?.checked
                ? '<span style="color:red">*</span>'
                : '';

        let html = `
            <div class="preview-field">

                <label>
                    ${label}
                    ${required}
                </label>
        `;

        if (type === "textarea") {

            html += `
                <textarea
                    placeholder="${placeholder}"
                    rows="4"
                ></textarea>
            `;
        }

        else if (type === "dropdown") {

            html += `<select>`;

            const options =
                optionsTextarea?.value
                    .split("\n")
                    .filter(option => option.trim()) || [];

            options.forEach(option => {

                html += `
                    <option>
                        ${option}
                    </option>
                `;
            });

            html += `</select>`;
        }

        else if (type === "radio") {

            const options =
                optionsTextarea?.value
                    .split("\n")
                    .filter(option => option.trim()) || [];

            options.forEach(option => {

                html += `
                    <div class="preview-option">
                        <input type="radio">
                        <span>${option}</span>
                    </div>
                `;
            });
        }

        else if (type === "checkbox") {

            const options =
                optionsTextarea?.value
                    .split("\n")
                    .filter(option => option.trim()) || [];

            options.forEach(option => {

                html += `
                    <div class="preview-option">
                        <input type="checkbox">
                        <span>${option}</span>
                    </div>
                `;
            });
        }

        else if (type === "file") {

            html += `
                <input type="file">
            `;
        }

        else {

            html += `
                <input
                    type="${type}"
                    placeholder="${placeholder}"
                >
            `;
        }

        html += `
            </div>
        `;

        previewContainer.innerHTML = html;
    }

    /* Live Events */

    fieldType?.addEventListener(
        "change",
        renderPreview
    );

    labelInput?.addEventListener(
        "input",
        renderPreview
    );

    placeholderInput?.addEventListener(
        "input",
        renderPreview
    );

    requiredField?.addEventListener(
        "change",
        renderPreview
    );

    optionsTextarea?.addEventListener(
        "input",
        renderPreview
    );

    renderPreview();

    /* Sidebar Dropdown */

    document
        .querySelectorAll(".dropdown-toggle")
        .forEach(function (item) {

            item.addEventListener(
                "click",
                function (e) {

                    e.preventDefault();

                    this.parentElement
                        .classList
                        .toggle("active");
                }
            );
        });

});