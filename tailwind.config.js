const defaultTheme = require('tailwindcss/defaultTheme')
const colors = require('tailwindcss/colors')

module.exports = {
  content: [
    './vendor/laravel/**/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
    './system/**/resources/**/*.vue',
    './system/**/resources/**/*.js',
  ],
  theme: {
    extend: {
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
      colors: {
        gray: colors.slate,
      },
    },
  },
  plugins: [
      require('@tailwindcss/forms'),
      require('@tailwindcss/typography'),
      require('@tailwindcss/aspect-ratio'),
  ],
}