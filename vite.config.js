import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
    base: '',
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js', // 👈 Force full build with template compiler
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
    server: {
        host: '192.168.1.246',
        port: 5173,
        cors: true,
        hmr: {
            host: '192.168.1.246',
        },
    },
})
