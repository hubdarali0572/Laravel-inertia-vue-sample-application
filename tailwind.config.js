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
        primary: '#001619',
        accent: '#C7F8FE',
        background: '#F5FAFA',
        surface: '#FFFFFF',
        border: '#DCEBED',
        brand: {
          DEFAULT: '#001619',
          dark: '#0a2e32',
          light: '#C7F8FE',
        },
        sidebar: {
          DEFAULT: '#001619',
          hover: 'rgba(199, 248, 254, 0.12)',
          border: 'rgba(220, 235, 237, 0.12)',
        },
      },

      fontFamily: {
        sans: ['Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
    },
  },

  plugins: [],
}
