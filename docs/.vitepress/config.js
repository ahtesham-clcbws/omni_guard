import { defineConfig } from 'vitepress'

export default defineConfig({
  title: "OmniGuard",
  description: "A friendly and helpful authorization assistant for Laravel",
  base: '/omni_guard/',
  themeConfig: {
    logo: '/shield.svg',
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Guide', link: '/v1/' },
      { text: 'Broadway Web Services', link: 'https://www.clcbws.com' }
    ],
    sidebar: [
      {
        text: 'Getting Started',
        items: [
          { text: 'Introduction', link: '/v1/' },
          { text: 'Installation', link: '/v1/installation' },
        ]
      },
      {
        text: 'Core Concepts',
        items: [
          { text: 'Hierarchy & Ranking', link: '/v1/hierarchy' },
          { text: 'The Discovery Helper', link: '/v1/heuristics' },
          { text: 'Bitmasking Performance', link: '/v1/bitmasking' },
        ]
      },
      {
        text: 'Advanced Orchestration',
        items: [
          { text: 'SaaS & Multitenancy', link: '/v1/saas-multitenancy' },
          { text: 'Security & Panic Mode', link: '/v1/security' },
          { text: 'Usage Reference', link: '/v1/usage' },
        ]
      }
    ],
    socialLinks: [
      { icon: 'github', link: 'https://github.com/ahtesham-clcbws/omni_guard' }
    ],
    footer: {
      message: 'A helping hand for your Laravel security.',
      copyright: 'Copyright © 2026-present Ahtesham (Broadway Web Services)'
    },
    search: {
      provider: 'local'
    }
  }
})
