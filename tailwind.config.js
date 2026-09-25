/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/**/*.php",
  ],
  theme: {
    extend: {
      colors: {
        gold: {
          50: '#fffbeb',
          100: '#fef3c7',
          200: '#fde68a',
          300: '#fcd34d',
          400: '#fbbf24',
          500: '#F5B51B',
          600: '#D99400',
          700: '#b45309',
          800: '#92400e',
          900: '#78350f',
        },
        'black-soft': '#141A24',
        brand: {
          gold: '#F5B51B',
          'gold-dark': '#D99400',
          'gold-light': '#FCE187',
          black: '#111111',
          'black-soft': '#141A24',
          'black-card': '#141414',
          'black-muted': '#262626',
        },
        kukar: {
          bg: '#F8F9FA',
          border: '#E5E7EB',
          muted: '#6B7280',
          subtle: '#9CA3AF',
        }
      },
      fontFamily: {
        sans: ['Plus Jakarta Sans', 'Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
        display: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
      },
      boxShadow: {
        'subtle': '0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.03)',
        'card': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
        'gold-glow': '0 0 15px rgba(245, 181, 27, 0.25)',
      }
    },
  },
  plugins: [],
}
