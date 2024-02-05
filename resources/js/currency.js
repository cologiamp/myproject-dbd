
export function changeToCurrency(amount) {

    alert('test');

    const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'GBP',
        minimumFractionDigits: 2
    });
    // Remove non-numeric characters from user input and convert to int
    let tempAmount = amount.replace(/[^\d.]/g, '');
    let numberValue = ''
    if (tempAmount) {
        numberValue = tempAmount;
        // Format input using Intl.NumberFormat
        return formatter.format(numberValue)
    }
}
