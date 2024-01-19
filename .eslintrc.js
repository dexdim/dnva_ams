module.exports = {
  root: true,
  env: {
    node: true
  },
  extends: [
    'plugin:prettier/recommended',
    'plugin:vue/base' // Add this line
  ],

  plugins: ['prettier'],

  rules: {
    'prettier/prettier': 'error'
  }
}
