module.exports = {
    scanForCssSelectors: [
        path.join(__dirname, 'node_modules/formBuilder/dist/*.js'),
    ],
    whitelistPatterns: [],
    webpackPlugins: [],
    install: ['formBuilder', 'pym.js'],
    copy: [],
    mix: {
        css: [],
        js: [
            {input: 'node_modules/pym.js/dist/pym.v1.js', output: 'public/js/embed.js'},
            {input: 'resources/js/vendor/formbuilder.js', output: 'public/js/formbuilder.js'},
        ],
    },
};
