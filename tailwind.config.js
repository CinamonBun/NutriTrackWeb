/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.{html,js}",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        // Custom colors untuk light dan dark mode
        'light-bg': '#f5f5f5',
        'dark-bg': '#1c1c1c',
        'light-text': '#1f2937',
        'dark-text': '#f5f5f5',
        'light-card': '#ffffff',
        'dark-card': '#2a2a2a',
        'light-border': '#e5e7eb',
        'dark-border': '#404040',
        'light-hover': '#f9fafb',
        'dark-hover': '#374151',
        'light-active': '#ffffff',
        'dark-active': '#374151',
        'light-inactive': '#9ca3af',
        'dark-inactive': '#6b7280',
      }
    },
  },
  plugins: [],
}
