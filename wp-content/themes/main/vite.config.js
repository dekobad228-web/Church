import path from 'path';
import { defineConfig } from 'vite';

export default defineConfig({

    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'src'),
        },
    },

    build: {
        manifest: true,
        assetsDir: '.',
        outDir: 'dist',
        emptyOutDir: true,
        sourcemap: true,
        rollupOptions: {
            input: {
                app: path.resolve(__dirname, 'src/app.js'),
            },
            output: {
                entryFileNames: '[name].[hash].js',
                assetFileNames: '[name].[hash].[ext]',
            }
        },
    },

    css: {
        preprocessorOptions: {
            scss: {
                includePaths: [path.resolve(__dirname, 'src')]
            }
        }
    },

    server: {
        host: 'localhost',
        port: 5173,
        strictPort: true,
        cors: true,
        hmr: {
            host: 'localhost'
        }
    },

    plugins: [
        {
            name: 'php',
            handleHotUpdate({ file, server }) {
                if (file.endsWith('.php')) {
                    server.ws.send({ type: 'full-reload' });
                }
            }
        },
    ]
});