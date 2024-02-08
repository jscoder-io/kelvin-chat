/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/wire-elements/modal/resources/views/*.blade.php',
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
  ],
  safelist: [
    'md:max-w-xl',
    'lg:max-w-3xl',
    'xl:max-w-5xl',
    '2xl:max-w-7xl',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

