const path = require('path');
const globImporter = require('node-sass-glob-importer');
const StyleLintPlugin = require('stylelint-webpack-plugin');
const { namespaces } = require('./setupTwig');
const ESLintPlugin = require('eslint-webpack-plugin');

module.exports = {
  stories: [
    '../components/**/*.stories.mdx',
    '../components/**/*.stories.@(js|jsx|ts|tsx)',
  ],
  addons: [
    '@storybook/addon-a11y',
    '@storybook/addon-links',
    '@storybook/addon-essentials',
  ],
  framework: {
    name: '@storybook/html-webpack5',
    options: {},
  },
  staticDirs: ['../dist', '../images'],
  webpackFinal: async (config) => {
    config.module.rules.push({
      test: /\.twig$/,
      use: [
        {
          loader: 'twig-loader',
          options: {
            twigOptions: {
              namespaces,
            },
          },
        },
      ],
    });

    config.module.rules.push({
      test: /\.s[ac]ss$/i,
      use: [
        'style-loader',
        {
          loader: 'css-loader',
          options: {
            sourceMap: true,
          },
        },
        {
          loader: 'sass-loader',
          options: {
            sourceMap: true,
            sassOptions: {
              importer: globImporter(),
            },
          },
        },
      ],
    });

    config.plugins.push(
      new StyleLintPlugin({
        configFile: path.resolve(__dirname, '../', '.stylelintrc.json'),
        context: path.resolve(__dirname, '../', 'components'),
        files: '**/*.scss',
        failOnError: false,
        quiet: false,
      }),
      new ESLintPlugin({
        context: path.resolve(__dirname, '../', 'components'),
        extensions: ['js'],
      }),
    );

    config.module.rules.push({
      test: /\.ya?ml$/,
      loader: 'js-yaml-loader',
    });

    return config;
  },
};
