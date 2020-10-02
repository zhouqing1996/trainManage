'use strict'
// Template version: 1.3.1
// see http://vuejs-templates.github.io/webpack for documentation.

const path = require('path')

module.exports = {
  dev: {

    // Paths
    assetsSubDirectory: 'static',
    assetsPublicPath: '/',

    /*代理配置表，在这里可以配置特定的请求代理到对应的API接口*/
    /* 下面的例子将代理请求 /api/getNewsList  到 http://localhost:3000/getNewsList*/
    proxyTable: {
      '/api': {
        changeOrigin: true,// 如果接口跨域，需要进行这个参数配置
        target: 'http://localhost:3000',// 接口的域名
        pathRewrite: {
          '^/api': ''//后面可以使重写的新路径，一般不做更改
        }
      },
      '/yii': {
        changeOrigin: true,// 如果接口跨域，需要进行这个参数配置
        target: 'http://localhost:8080/trainManage/trainApi/backend/web/index.php',// 接口的域名
        pathRewrite: {
          '^/yii': ''//后面可以使重写的新路径，一般不做更改
        }
      },
      '/download': {
        changeOrigin: true,// 如果接口跨域，需要进行这个参数配置
        target: 'http://localhost:8080/trainManage/trainApi/backend',// 接口的域名
        pathRewrite: {
          '^/download': ''//后面可以使重写的新路径，一般不做更改
        }
      },
      '/peanut': {
        changeOrigin: true,// 如果接口跨域，需要进行这个参数配置
        target: 'http://localhost:8080/trainManage/trainApi/backend/web/index.php',// 接口的域名
        pathRewrite: {
          '^/peanut': ''//后面可以使重写的新路径，一般不做更改
        }
      },
      '/local': {
        changeOrigin: true,// 如果接口跨域，需要进行这个参数配置
        target: 'http://localhost:8080/trainManage/trainApi/backend/web/index.php',// 接口的域名
        pathRewrite: {
          '^/local': ''//后面可以使重写的新路径，一般不做更改
        }
      },
      '/translate': {
        changeOrigin: true,// 如果接口跨域，需要进行这个参数配置
        target: 'https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20170721T082515Z.54cf3dc583f679db.f4a96182281281d8b5dfe24b4e88298e2133f219&lang=zh-en&text=',// 接口的域名
        pathRewrite: {
          '^/translate': ''//后面可以使重写的新路径，一般不做更改
        }
      }
    },

    // Various Dev Server settings
    host: 'localhost', // can be overwritten by process.env.HOST
    port: 8081, // can be overwritten by process.env.PORT, if port is in use, a free one will be determined
    autoOpenBrowser: false,
    errorOverlay: true,
    notifyOnErrors: true,
    poll: false, // https://webpack.js.org/configuration/dev-server/#devserver-watchoptions-

    // Use Eslint Loader?
    // If true, your code will be linted during bundling and
    // linting errors and warnings will be shown in the console.
    useEslint: false,
    // If true, eslint errors and warnings will also be shown in the error overlay
    // in the browser.
    showEslintErrorsInOverlay: false,

    /**
     * Source Maps
     */

    // https://webpack.js.org/configuration/devtool/#development
    devtool: 'cheap-module-eval-source-map',

    // If you have problems debugging vue-files in devtools,
    // set this to false - it *may* help
    // https://vue-loader.vuejs.org/en/options.html#cachebusting
    cacheBusting: true,

    cssSourceMap: true
  },

  build: {
    // Template for index.html
    index: path.resolve(__dirname, '../dist/trainManage/trainPlatform/index.html'),

    // Paths
    assetsRoot: path.resolve(__dirname, '../dist/trainManage/trainPlatform'),
    assetsSubDirectory: 'static',
    assetsPublicPath: '/trainManage/trainPlatform/',

    /**
     * Source Maps
     */

    productionSourceMap: true,
    // https://webpack.js.org/configuration/devtool/#production
    devtool: '#source-map',

    // Gzip off by default as many popular static hosts such as
    // Surge or Netlify already gzip all static assets for you.
    // Before setting to `true`, make sure to:
    // npm install --save-dev compression-webpack-plugin
    productionGzip: false,
    productionGzipExtensions: ['js', 'css'],

    // Run the build command with an extra argument to
    // View the bundle analyzer report after build finishes:
    // `npm run build --report`
    // Set to `true` or `false` to always turn it on or off
    bundleAnalyzerReport: process.env.npm_config_report
  }
}
