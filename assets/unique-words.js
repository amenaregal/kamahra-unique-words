// ✅ DOM elements
const inputField = document.getElementById('kuw-input');
const outputField = document.getElementById('kuw-output');
const warning = document.getElementById('kuw-warning');
const copyButton = document.getElementById('kuw-copy-button');
const feedback = document.getElementById('kuw-copy-feedback');
const separatorRadios = document.querySelectorAll('input[name="kuw-separator"]'); // Radio buttons

let activeSeparator = " "; // Default → Space

// ✅ Extract + clean input → output
function processInput() {
    const input = inputField.value;

    // Show soft limit warning
    if (input.length > 10000) {
        warning.style.display = 'block';
    } else {
        warning.style.display = 'none';
    }

    // Normalize text
    const cleaned = input
        .toLowerCase()
        .replace(/[^\p{L}\p{N}\p{Emoji}\s-]+/gu, '') // Keep letters, numbers, spaces, hyphens
        .replace(/\s+/g, ' ')
        .trim();

    const words = Array.from(cleaned.matchAll(/\p{L}+\p{M}*|\p{N}+|[\p{Emoji}]/gu), m => m[0]);
    const unique = [...new Set(words.filter(Boolean))];

    outputField.value = unique.join(activeSeparator);
}

// ✅ Listen for typing in input field
if (inputField) {
    inputField.addEventListener('input', processInput);
}

// ✅ Listen for separator radio change
separatorRadios.forEach(radio => {
    radio.addEventListener('change', () => {
        switch (radio.value) {
            case 'newline':
                activeSeparator = '\n';
                break;
            case 'space':
                activeSeparator = ' ';
                break;
            case 'comma':
                activeSeparator = ', ';
                break;
            case 'tab':
                activeSeparator = '\t';
                break;
            default:
                activeSeparator = ' ';
        }
        processInput();
    });
});

// ✅ Copy to Clipboard
if (copyButton) {
    copyButton.addEventListener('click', async () => {
        if (!outputField.value.trim()) return;

        try {
            await navigator.clipboard.writeText(outputField.value);
            if (feedback) {
                feedback.style.display = 'block';
                setTimeout(() => {
                    feedback.style.display = 'none';
                }, 2000);
            }
        } catch (err) {
            // fallback if navigator.clipboard fails
            outputField.select();
            document.execCommand('copy');
            if (feedback) {
                feedback.style.display = 'block';
                setTimeout(() => {
                    feedback.style.display = 'none';
                }, 2000);
            }
        }
    });
}
