module.exports = {
  devServer: {
    proxy: {
      '/api': {
        target: 'http://localhost/findyourvetph/api',
        changeOrigin: true,
        pathRewrite: {
          '^/api': ''
        }
      }
    }
  }
}