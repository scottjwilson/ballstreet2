import { defineConfig } from "vite";
import { resolve } from "path";

export default defineConfig(({ command }) => {
  const isProduction = command === "build";

  return {
    // Only use base path in production builds
    // In dev mode, Vite serves from root for simpler URLs
    base: isProduction ? "/wp-content/themes/ballstreet2/" : "/",

    build: {
      manifest: true,
      outDir: "dist",
      emptyOutDir: true,
      rollupOptions: {
        input: {
          // Main entry point - CSS is imported here for HMR
          main: resolve(__dirname, "js/main.js"),
        },
        output: {
          entryFileNames: "js/[name]-[hash].js",
          chunkFileNames: "js/[name]-[hash].js",
          assetFileNames: (assetInfo) => {
            if (assetInfo.name && assetInfo.name.endsWith(".css")) {
              return "css/[name]-[hash][extname]";
            }
            return "assets/[name]-[hash][extname]";
          },
        },
      },
    },

    // Server configuration for hot reload
    server: {
      host: "localhost",
      port: 3000,
      strictPort: true,
      cors: true,
      hmr: {
        host: "localhost",
        protocol: "ws",
      },
      watch: {
        // Watch CSS files for changes
        include: ["css/**/*.css", "js/**/*.js"],
      },
    },

    // Public directory
    publicDir: false,

    css: {
      devSourcemap: true,
    },
  };
});
