export default {
  // Enable Dark Mode using the 'dark' class
  darkMode: 'class',

  // Files Tailwind scans for CSS classes
  content: [
    "./resources/**/*.blade.php", // Blade files
    "./resources/**/*.js",        // JavaScript files
    "./resources/**/*.vue",       // Vue components
  ],

  theme: {
    extend: {
      colors: {
        primary: '#7367F0',
        accent: '#7367F0',
        background: '#F8F9FA',
        surface: '#FFFFFF',
        border: '#EBE9F1',
        brand: {
          DEFAULT: '#7367F0',
          dark: '#6258CC',
          light: '#EAE8FD',
        },
        sidebar: {
          DEFAULT: '#F8F9FA',
          hover: '#F3F2FF',
          active: '#7367F0',
          border: '#EBE9F1',
        },
      },

      fontFamily: {
        sans: ['Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
    },
  },

  plugins: [],
}
