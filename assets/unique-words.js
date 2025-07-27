document.addEventListener("DOMContentLoaded", function () {
    const inputField = document.getElementById("kuw-input");
    const outputField = document.getElementById("kuw-output");
    const separatorField = document.getElementById("kuw-separator");
    const copyButton = document.getElementById("kuw-copy-button");

    // ✅ Main logic: When user types/pastes text
    function processText() {
        const input = inputField.value.trim().toLowerCase();
        if (!input) return;

        const separator = separatorField.value || "\n";

        const cleaned = input
            .replace(/[^\w\s-]/g, '')        // Remove punctuation
            .replace(/\s+/g, ' ')            // Collapse multiple spaces
            .split(' ')                      // Split by space
            .filter((word, index, arr) => word && arr.indexOf(word) === index); // Remove duplicates

        outputField.value = cleaned.join(separator);
        copyButton.disabled = cleaned.length === 0;
    }

    inputField.addEventListener("input", processText);
    inputField.addEventListener("paste", function () {
        setTimeout(processText, 100); // delay to wait for pasted text
    });

    // ✅ Copy to Clipboard functionality
    copyButton.addEventListener("click", function () {
        if (!outputField.value.trim()) return;

        navigator.clipboard.writeText(outputField.value).then(() => {
            const originalText = copyButton.textContent;
            copyButton.textContent = "Copied!";
            setTimeout(() => {
                copyButton.textContent = originalText;
            }, 1500);
        });
    });

    // Disable copy button on load
    copyButton.disabled = true;
});
