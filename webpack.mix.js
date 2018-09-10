let mix = require('laravel-mix');
let tailwindcss = require('tailwindcss');

mix.webpackConfig({
    devtool: 'source-map',
    resolve: {
        alias: {
            '~': path.resolve(__dirname, "resources/js")
        }
    }
})

mix.setPublicPath('public')

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
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
