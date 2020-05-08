const mix = require('laravel-mix');
/*
mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');
*/
mix.js('resources/assets/js/app.js', 'public/production/js')
   .sass('resources/assets/sass/app.scss', 'public/production/css');


mix.webpackConfig({
   plugins: [],
   resolve: {
      extensions: ['.js', '.json', '.vue'],
      alias: {
         // '~': path.resolve(__dirname, 'resources/assets/js'),
         '~': __dirname + '/resources/assets/js',
      }
   },
   output: {
      chunkFilename: 'production/js/[id].js'
      // path: mix.config.hmr ? '/' : path.resolve(__dirname, './public/build')
   }
});