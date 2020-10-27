const fs = require('fs')
const jsonContent = fs.readFileSync('./theme.config.json')
const themeConfig = JSON.parse(jsonContent)

const path = require('path')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')
const postcssPresetEnv = require('postcss-preset-env')


module.exports = {
   entry: {
      frontEnd: './src/index.js',
      backEnd: './src/admin-index.js',
      login: './src/admin-login.js',
      //add separate js files here if you dont want them concatenated into the others
   },
   resolve: {
      alias: {
         src: path.resolve(__dirname, 'src/'),
         inc: path.resolve(__dirname, 'inc/'),
         root: path.resolve(__dirname, '.'),
      }
   },
   devtool: 'source-map',
   output: {
      path: path.resolve(__dirname, 'dist'),
      filename: '[name]_bundle.js',
   },
   module: {
      //runs these and transforms on the code
      rules: [
         {
            test: /\.(png|jpe?g|gif)$/i,
            use: [
               {
                  loader: 'file-loader',
                  options: {
                     name: '[name].[ext]',
                     outputPath: 'images',
                  }
               },
            ],
         },
         { test: /\.svg$/, use: 'svg-inline-loader' },
         { test: /\.(js)$/, use: ['babel-loader', 'import-glob-loader'] }, //turns jsx into js the browser can understand and also allows for es6 to be used
         {
            test: /\.css$/, use: [{
               loader: MiniCssExtractPlugin.loader
            }, 'css-loader']
         },
         {
            test: /\.s[ac]ss$/i, use: [
               {
                  loader: MiniCssExtractPlugin.loader
               }, 'css-loader',
               {
                  loader: 'postcss-loader', options: {
                     postcssOptions: {
                        plugins: [
                           [
                              'postcss-preset-env',
                              {
                                 // Options
                              },
                           ],
                        ]
                     }}},
               { loader: 'resolve-url-loader' },
               {
                  loader: 'sass-loader',
                  options: {
                     implementation: require('sass')
                  }
               },
               'import-glob-loader'
            ]
         },
      ]
   },
   plugins: [
      new MiniCssExtractPlugin(),
      new BrowserSyncPlugin({
             host: themeConfig.server,
             proxy: `https://${themeConfig.server}`,
             https: true,
             files: [
                '**/*.php',
                '**/*.css',
                {
                   match: '**/*.js',
                   options:{
                      ignored: 'dist/**/*.js'
                   }
                }
             ],
             //if using a local server with .local, will load that domain
             open: themeConfig.server.includes('.local') ? 'external':'local',
             //magic for seeing changes on a live site. no local server needed. However php cannot be changed
             serveStatic: ['.'],
             rewriteRules: [
                {
                   match: /wp-content\/themes\/\w+\//g,
                   replace: ''
                }
             ],
             reloadDelay: 0
          }, { reload: false } //make css load without reload}
      ),
   ]
}



