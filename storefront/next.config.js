/** @type {import('next').NextConfig} */
const path = require('path');
const { i18n } = require('./next-i18next.config');
const runtimeCaching = require('next-pwa/cache');

const withPWA = require('next-pwa')({
  disable: process.env.NODE_ENV === 'development',
  dest: 'public',
  runtimeCaching,
});

const backendOrigin =
  process.env.INTERNAL_REST_API_ENDPOINT ||
  (process.env.NODE_ENV === 'development'
    ? 'http://127.0.0.1:8000'
    : 'http://backend:8000');

module.exports = withPWA({
  outputFileTracingRoot: path.join(__dirname, '..'),
  i18n,
  async rewrites() {
    return [
      {
        source: '/api-backend/:path*',
        destination: `${backendOrigin}/:path*`,
      },
    ];
  },
  images: {
    domains: [
      '127.0.0.1',
      'localhost',
      'googleusercontent.com',
      'maps.googleapis.com',
      'graph.facebook.com',
      'res.cloudinary.com',
      's3.amazonaws.com',
      '18.141.64.26',
      'via.placeholder.com',
      'pickbazarlaravel.s3.ap-southeast-1.amazonaws.com',
      'picsum.photos',
      'cdninstagram.com',
      'scontent.cdninstagram.com',
      'lh3.googleusercontent.com',
      'yce-us.s3-accelerate.amazonaws.com',
    ],
  },

  typescript: {
    ignoreBuildErrors: true,
  },
});
