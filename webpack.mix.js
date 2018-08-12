let mix = require('laravel-mix');
let tailwindcss = require('tailwindcss');

mix.webpackConfig({
    resolve: {
        alias: {
            '~': path.resolve(__dirname, "resources/assets/js")
        }
    }
})

mix.setPublicPath('public')

mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .options({
        processCssUrls: false,
        postCss: [tailwindcss('tailwind.js')],
    })

if (mix.inProduction()) {
    mix.version()
} else {
    mix.sourceMaps()
        .disableSuccessNotifications()
}
