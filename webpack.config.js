const Encore = require('@symfony/webpack-encore');

Encore
    // Directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // Public path used by the web server to access the output path
    .setPublicPath('/build')
    // Clear the output directory before each build
    .cleanupOutputBeforeBuild()
    // Enable source maps for debugging
    .enableSourceMaps(!Encore.isProduction())
    // Enable hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())
    // Add an entry point for your CSS
    .addEntry('app', './assets/app.js')
    // Enable single runtime chunk
    .enableSingleRuntimeChunk();

module.exports = Encore.getWebpackConfig();


