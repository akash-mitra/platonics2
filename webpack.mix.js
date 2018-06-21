let mix = require('laravel-mix')
require('laravel-mix-purgecss')

mix.js('resources/assets/js/app.js', 'public/js')
  .js('resources/assets/js/categories.js', 'public/js')
  .js('resources/assets/js/pages.js', 'public/js')
  .js('resources/assets/js/pages.create.js', 'public/js/')
  .js('resources/assets/js/categories.form.js', 'public/js/')
  .styles([
    'resources/assets/css/medium-editor.css', 
    'resources/assets/css/medium-editor-flat.css'
  ], 'public/css/medium-editor.css')
  .postCss('resources/assets/css/app.css', 'public/css')
  .options({
    postCss: [
      require('postcss-import')(),
      require('tailwindcss')(/* './path/to/tailwind.js' */),
      require('postcss-cssnext')({
        // Mix adds autoprefixer already, don't need to run it twice
        features: { autoprefixer: false }
      }),
    ]
  })
  .purgeCss()

if (mix.inProduction()) {
    mix.version()
}
