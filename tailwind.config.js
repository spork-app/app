const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  purge: [
    './vendor/laravel/**/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',

  ],
  darkMode: 'media', // or 'media' or 'class'
  theme: {
    extend: {
        fontFamily: {
            sans: ['Inter var', ...defaultTheme.fontFamily.sans],
        },
        keyframes: {
          rotate: {
            'from': { transform: 'rotate(0deg)' },
            'to': { transform: 'rotate(-359deg)' }, 
          }
        },
        animation: {
          rotate: 'rotate .75s ease-in-out infinite'
        },
    },
  },
  variants: {
    extend: {},
  },
  plugins: [
      require('@tailwindcss/forms'),
      require('@tailwindcss/typography'),
      require('@tailwindcss/aspect-ratio'),
  ],
}
