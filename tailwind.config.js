/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./app/**/*.php",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      colors: {
        'crmt': {
          'blue': '#4F46E5',
          'purple': '#7C3AED',
          'indigo': '#6366F1',
        }
      }
    },
  },
  plugins: [],
}
