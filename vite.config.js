import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/page-builder.css',
                'resources/js/page-builder.js',
            ],
            refresh: true,
        }),
    ],
    esbuild: {
        loader: 'jsx',
        include: /.*\.js$/,
        exclude: [],
    },
    optimizeDeps: {
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
});