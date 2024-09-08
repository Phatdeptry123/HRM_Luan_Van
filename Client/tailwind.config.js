/** @type {import('tailwindcss').Config} */
import daisyui from 'daisyui'

export default {
  content: [],
  theme: {
    extend: {}
  },
  plugins: [daisyui],
  purge: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  darkMode: false, // or 'media' or 'class'
  variants: {
    extend: {}
  }
}
