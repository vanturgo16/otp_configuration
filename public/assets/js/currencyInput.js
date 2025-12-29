function formatCurrencyInput(event) {
    let value = event.target.value;
    // Remove any characters that are not digits or commas
    value = value.replace(/[^\d,]/g, '');
    // Split the input value into integer and decimal parts
    let parts = value.split(',');
    // Format the integer part with dots as thousand separators
    let integerPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    // Reassemble the formatted value
    if (parts.length > 1) {
        let decimalPart = parts[1].slice(0, 3); // Limit to 3 decimal places
        value = `${integerPart},${decimalPart}`;
    } else {
        value = integerPart;
    }
    event.target.value = value;
}