const { defineConfig } = require("cypress");

module.exports = defineConfig({
  viewportHeight: 900,
  viewportWidth: 1440,
  video: false,
  reporter: "junit",

  reporterOptions: {
    mochaFile: "reports/cypress-integration-[hash].xml",
  },

  e2e: {
    baseUrl: "https://t3ext-responsive-images.ddev.site/",
    setupNodeEvents(on, config) {
      // implement node event listeners here
    },
  },
});
