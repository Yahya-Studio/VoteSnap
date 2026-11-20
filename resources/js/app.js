import Chart from 'chart.js/auto';
import QRCode from 'qrcode';

window.Chart = Chart;
window.QRCode = QRCode;

// Initialize dark mode preference from localStorage
if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark');
} else {
    document.documentElement.classList.remove('dark');
}
