const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
            spacing: {
                '13': '3.25rem',
            }
        },
    },
    plugins: [
        require('tailwindcss-owl'),
        require('@tailwindcss/ui'),
    ]
};
