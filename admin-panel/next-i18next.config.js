/** @type {import('next-i18next').UserConfig} */

const path = require('path');

const isMultilangEnable =
  process.env.NEXT_PUBLIC_ENABLE_MULTI_LANG === 'true' &&
  !!process.env.NEXT_PUBLIC_AVAILABLE_LANGUAGES;
const defaultLanguage = process.env.NEXT_PUBLIC_DEFAULT_LANGUAGE ?? 'en';

function generateLocales() {
  if (isMultilangEnable) {
    return process.env.NEXT_PUBLIC_AVAILABLE_LANGUAGES.split(',');
  }

  return [defaultLanguage];
}

module.exports = {
  i18n: {
    defaultLocale: defaultLanguage,
    locales: generateLocales(),
  },
  localePath: path.resolve('./public/locales'),
  reloadOnPrerender: process.env.NODE_ENV === 'development',
};
