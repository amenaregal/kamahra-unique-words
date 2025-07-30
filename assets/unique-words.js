const inputField = document.getElementById('kuw-input');
const outputField = document.getElementById('kuw-output');
const separatorField = document.getElementById('kuw-separator');
const warning = document.getElementById('kuw-warning');

// Store the separator when input is typed/pasted
let activeSeparator = "\n";

function processInput() {
    const input = inputField.value;

    // ✅ Soft character limit logic
    if (input.length > 10000) {
        warning.style.display = 'block';
    } else {
        warning.style.display = 'none';
    }

    // ✅ Sanitize input
    const cleaned = input
        .toLowerCase()
        .replace(/[^\w\s-]/g, '')      // keep letters, numbers, spaces, hyphens
        .replace(/\s+/g, ' ')          // normalize spaces
        .trim();

    // ✅ Extract unique words
    const words = cleaned.split(' ');
    const unique = [...new Set(words.filter(word => word.trim() !== ''))];

    outputField.value = unique.join(activeSeparator || '\n');
}

// ✅ When input changes
inputField.addEventListener('input', () => {
    activeSeparator = separatorField.value || '\n';
    processInput();
});

// ✅ Also handle paste events
inputField.addEventListener('paste', () => {
    activeSeparator = separatorField.value || '\n';
    setTimeout(processInput, 50);
});
