const { createProxyMiddleware } = require('http-proxy-middleware');

module.exports = (app) => {
  // eslint-disable-next-line no-unused-expressions
  app.use(
    createProxyMiddleware(['/api'], {
      target: 'https://theory.twt.edu.cn',
      secure: false,
      changeOrigin: true,
      onProxyReq(proxyReq, req, res) {
        proxyReq.setHeader('cookie', '_session=eyJpdiI6InFIVHhnT1ExUDc3bVhqR3ZZUXpiSVE9PSIsInZhbHVlIjoiT1lhV3l6Mkc1Ny8xVFZaWkN5MkZuVGdKaVc4SE5uRmVQeHVoa0swTGJQNHBrVWQxNHRtVlBnWWYzVTd4MVJUOFJ2eHl2enB3d005cEhRWTVkT2JzMm5ueVNLRzBxUEgzREdsd0ZQOVVPWThyWFRyNGdqZDZXOXMrbUF3Vmd6WnAiLCJtYWMiOiJkNjQ4NGIyMGU0MjZlYjMyYTc4Y2M0NzE0YTI3NzNjY2MyMTNmNDJlNDYwNmIwYWRkYThjNjJjMDViNDNlZWI2In0%3D');
      }
    })
  )
};
