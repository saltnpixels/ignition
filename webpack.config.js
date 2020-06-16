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
      //add separate js files here if you dont want them concatenated into the others
   },
   devtool: 'source-map',
   output: {
      path: path.resolve(__dirname, 'dist'),
      filename: '[name]_bundle.js',
   },
   module: {
      //runs these and transforms on the code
      rules: [
         { test: /\.svg$/, use: 'svg-inline-loader' },
         { test: /\.(js)$/, use: ['babel-loader', 'import-glob-loader'] }, //turns jsx into js the browser can understand and also allows for es6 to be used
         { test: /\.css$/, use: [MiniCssExtractPlugin.loader, 'css-loader'] },
         {
            test: /\.s[ac]ss$/i, use: [
                {
                   loader: MiniCssExtractPlugin.loader,
                }, 'css-loader',
               {
                  loader: 'postcss-loader', options: {
                     ident: 'postcss',
                     plugins: () => [
                        postcssPresetEnv(/* pluginOptions */),
                     ]
                  }
               },
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
             host: themeConfig.local,
             proxy: themeConfig.local,
             https: true,

             files: [
                '**/*.php'
             ],
             reloadDelay: 0
          }
      ),
   ]
}



