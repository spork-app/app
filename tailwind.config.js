const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  purge: [
    './vendor/laravel/**/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
    './system/*/resources/views/**/*.blade.php',
    './system/*/resources/js/**/*.vue',
    './system/*/resources/js/**/*.js',
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
        typography: (theme) => ({
          sm: {
            css: {
              color: theme('colors.gray.800'),
              hr: {
                marginTop: '1rem',
                marginBottom: '1rem',
              },
              h1: {
                lineHeight: 1
              },
              h2: {
                marginTop: '1rem',
                marginBottom: '0.5rem',
              },
              p: {
                marginTop: '1rem',
                marginBottom: '1rem',
              }
            },
          },
        }),
  
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
