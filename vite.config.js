import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        manifest: true,
        outDir: 'public/build',
        rollupOptions: {
          input: 'resources/js/app.js', // ここで入力ファイルを指定
          output: {
            entryFileNames: 'js/app.js', // 出力ファイル名を指定
        },
    },
  },

});
