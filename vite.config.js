import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';
import path from 'path';
import commonjs from 'vite-plugin-commonjs';
import inject from "@rollup/plugin-inject";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'app.js',
                'resources/css/page-builder.css',
                'page-builder.js',
            ],
            refresh: true,
        }),
        inject({
            $: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery',
        }),
        commonjs({
            include: [
                /elementor\/.*/,
                /node_modules\/jquery/,
            ],
        }),
    ],
    esbuild: {
        loader: 'jsx',
        include: /.*\.js$/,
        exclude: [],
    },
    optimizeDeps: {
        include: ['jquery'],
        esbuildOptions: {
            plugins: [
                {
                    name: 'load-js-files-as-jsx',
                    setup(build) {
                        build.onLoad({ filter: /.*\.js$/ }, async (args) => ({
                            loader: 'jsx',
                            contents: await fs.promises.readFile(args.path, 'utf8'),
                        }));
                    },
                },
            ],
        },
    },
    resolve: {
        alias: {
            'jquery': path.resolve(__dirname, 'node_modules/jquery'),
            'elementor': path.resolve(__dirname, 'elementor'),
            'elementor-app': path.resolve(__dirname, 'elementor/app/assets/js'),
            'elementor-admin': path.resolve(__dirname, 'elementor/assets/dev/js/admin'),
            'elementor-api': path.resolve(__dirname, 'elementor/modules/web-cli/assets/js'),
            'elementor-assets-js': path.resolve(__dirname, 'elementor/assets/dev/js'),
            'elementor-behaviors': path.resolve(__dirname, 'elementor/assets/dev/js/editor/elements/views/behaviors'),
            'elementor-common': path.resolve(__dirname, 'elementor/core/common/assets/js'),
            'elementor-common-modules': path.resolve(__dirname, 'elementor/core/common/modules'),
            'elementor-controls': path.resolve(__dirname, 'elementor/assets/dev/js/editor/controls'),
            'elementor-document': path.resolve(__dirname, 'elementor/assets/dev/js/editor/document'),
            'elementor-dynamic-tags': path.resolve(__dirname, 'elementor/assets/dev/js/editor/components/dynamic-tags'),
            'elementor-editor': path.resolve(__dirname, 'elementor/assets/dev/js/editor'),
            'elementor-editor-utils': path.resolve(__dirname, 'elementor/assets/dev/js/editor/utils'),
            'elementor-elements': path.resolve(__dirname, 'elementor/assets/dev/js/editor/elements'),
            'elementor-frontend': path.resolve(__dirname, 'elementor/assets/dev/js/frontend'),
            'elementor-panel': path.resolve(__dirname, 'elementor/assets/dev/js/editor/regions/panel'),
            'elementor-regions': path.resolve(__dirname, 'elementor/assets/dev/js/editor/regions'),
            'elementor-revisions': path.resolve(__dirname, 'elementor/assets/dev/js/editor/components/revisions'),
            'elementor-scss': path.resolve(__dirname, 'elementor/assets/dev/scss'),
            'elementor-templates': path.resolve(__dirname, 'elementor/assets/dev/js/editor/components/template-library'),
            'elementor-utils': path.resolve(__dirname, 'elementor/assets/dev/js/utils'),
            'elementor-validator': path.resolve(__dirname, 'elementor/assets/dev/js/editor/components/validator'),
            'elementor-views': path.resolve(__dirname, 'elementor/assets/dev/js/editor/views'),
            '@elementor/e-icons': path.resolve(__dirname, 'elementor/assets/dev/js/frontend/utils/icons/e-icons'),
            'e-styles': path.resolve(__dirname, 'elementor/packages/elementor-ui/styles'),
            'e-components': path.resolve(__dirname, 'elementor/packages/elementor-ui/components'),
            'e-utils': path.resolve(__dirname, 'elementor/packages/elementor-ui/components/utils'),
            'elementor-frontend-utils': path.resolve(__dirname, 'elementor/assets/dev/js/frontend/utils'),
        },
    },
});