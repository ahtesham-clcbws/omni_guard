export default {
  title: "OmniGuard",
  description: "A friendly and helpful authorization assistant for Laravel",
  base: '/omni_guard/',
  themeConfig: {
    logo: '/shield.svg',
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Broadway Web Services', link: 'https://www.clcbws.com' }
    ],
    sidebar: [
      {
        text: 'Getting Started',
        items: [
          { text: 'Introduction', link: '/' },
          { text: 'Installation', link: '/installation' },
        ]
      },
      {
        text: 'Core Concepts',
        items: [
          { text: 'Hierarchy & Ranking', link: '/hierarchy' },
          { text: 'The Discovery Helper', link: '/heuristics' },
          { text: 'Bitmasking Performance', link: '/bitmasking' },
        ]
      },
      {
        text: 'Advanced Orchestration',
        items: [
          { text: 'SaaS & Multitenancy', link: '/saas-multitenancy' },
          { text: 'Security & Panic Mode', link: '/security' },
          { text: 'Usage Reference', link: '/usage' },
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
}
