/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.php",
    "./assets/js/**/*.js",
  ],
  safelist: [
    "is-active", "menu-open", "tpg-locations-open",
    "!opacity-100", "opacity-0", "w-6", "w-1.5", "bg-tpg-green", "bg-tpg-line",
    { pattern: /(bg|text|border)-(tpg)-(green|greenDim|ink|paper|line)/ },
  ],
  theme: {
    extend: {
      colors: {
        tpg: {
          black:    "#0A0C0B",
          ink:      "#0F1311",
          surface:  "#141917",
          line:     "#232C28",
          green:    "#00E676",
          greenDim: "#0BB763",
          greenGlow:"#39FF8B",
          paper:    "#F4F7F5",
          muted:    "#9AA8A1",
        },
      },
      fontFamily: {
        display: ['Sora', 'system-ui', 'sans-serif'],
        body: ['Manrope', 'system-ui', 'sans-serif'],
        mono: ['"DM Mono"', 'ui-monospace', 'monospace'],
      },
      boxShadow: {
        glow: "0 0 0 1px rgba(0,230,118,.25), 0 20px 60px -20px rgba(0,230,118,.35)",
        card: "0 24px 60px -28px rgba(0,0,0,.7)",
      },
      backgroundImage: {
        "grid-faint": "linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px)",
      },
      backgroundSize: { "grid": "44px 44px" },
      keyframes: {
        riseIn: { "0%": { opacity: 0, transform: "translateY(24px)" }, "100%": { opacity: 1, transform: "translateY(0)" } },
        glowPulse: { "0%,100%": { opacity: .5 }, "50%": { opacity: 1 } },
      },
      animation: {
        riseIn: "riseIn .8s cubic-bezier(.16,1,.3,1) both",
        glowPulse: "glowPulse 3s ease-in-out infinite",
      },
    },
  },
  plugins: [],
};
