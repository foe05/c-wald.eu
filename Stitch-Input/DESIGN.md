# Design System Specification: Forest Tech Editorial

## 1. Overview & Creative North Star
The Creative North Star for this design system is **"The Digital Arboretum."** 

We are moving away from the "SaaS-standard" look of rigid grids and heavy borders. Instead, we treat the interface as a high-end editorial piece where technology meets the organic world. The goal is to create an experience that feels as stable as an ancient forest and as precise as modern laboratory equipment. 

We achieve this through **Intentional Asymmetry**—breaking the expected horizontal alignment with overlapping elements—and **Atmospheric Depth**, utilizing a palette of deep greens and bone-whites to create a sense of scale and breathing room.

---

## 2. Colors: Tonal Ecology
Our palette is rooted in the "Forest Tech" aesthetic, moving from the light of the canopy to the shadows of the forest floor.

### The Palette
*   **Primary & Dark Accents:** `primary` (#00361a) and `primary_container` (#1a4d2e). These are our "Anchor" colors, representing authority and the deep woods.
*   **The Vibrant Pulse:** `tertiary_fixed` (#80fba3). This is our "Chlorophyll" accent—used sparingly for high-impact actions and data highlights.
*   **The Base:** `surface` (#fbf9f4). A warm, "bone" white that feels more premium and organic than a clinical #FFFFFF.

### Rules of Engagement
*   **The "No-Line" Rule:** 1px solid borders are strictly prohibited for sectioning. Boundaries must be defined through background shifts (e.g., a `surface_container_low` section sitting on a `surface` background).
*   **Surface Hierarchy & Nesting:** Use the `surface_container` tiers to create depth. A `surface_container_lowest` card should sit atop a `surface_container_low` background to create a "paper-on-stone" feel.
*   **The "Glass & Gradient" Rule:** For floating navigation or hero overlays, use Glassmorphism. Apply `surface_container_lowest` at 70% opacity with a `24px` backdrop blur.
*   **Signature Textures:** For primary CTAs, do not use flat colors. Use a subtle linear gradient from `primary` (#00361a) to `primary_container` (#1a4d2e) at a 135-degree angle to add "soul" and dimension.

---

## 3. Typography: The Authoritative Voice
We employ a high-contrast typographic scale to mimic luxury scientific journals.

*   **Display & Headlines (Manrope):** We use Manrope for all `display` and `headline` roles. Its geometric yet approachable curves feel modern and "engineered." Large `display-lg` (3.5rem) should be used with tight letter-spacing (-0.02em) to command attention.
*   **Body & Titles (Inter):** Inter is our workhorse. It provides the "Tech" in Forest Tech. Use `body-lg` (1rem) for long-form content to ensure maximum readability against our bone-white surfaces.
*   **Labels (Inter):** All labels must be uppercase with a +0.05em letter-spacing to provide a clean, architectural feel to small metadata.

---

## 4. Elevation & Depth: Tonal Layering
Traditional drop shadows are often too "dirty." We prefer **Tonal Layering** to define space.

*   **The Layering Principle:** Stack `surface-container-lowest` on `surface-container-low` for a soft, natural lift. This mimics the way leaves layer on a forest floor.
*   **Ambient Shadows:** When a physical lift is required (e.g., a modal), use an ultra-diffused shadow: `box-shadow: 0 20px 40px rgba(0, 33, 14, 0.06)`. The tint is derived from our `on_primary_fixed` to ensure the shadow feels like part of the environment, not a grey smudge.
*   **The "Ghost Border" Fallback:** If a container requires a boundary (e.g., an input field), use the `outline_variant` token at 20% opacity. 
*   **Glassmorphism:** Use semi-transparent layers for elements that need to feel "light" or "temporary," such as tooltips or hovering headers, allowing the lush green accents of the background to bleed through.

---

## 5. Components

### Buttons
*   **Primary:** A gradient of `primary` to `primary_container`. `md` (0.75rem) border radius. White text. No border.
*   **Secondary:** `surface_container_highest` background with `on_surface` text.
*   **Tertiary:** No background. `tertiary_fixed_variant` text with a subtle underline on hover.

### Chips
*   **Selection:** Use `primary_fixed` with `on_primary_fixed` text. Radius should be `full`.
*   **Action:** `surface_container_high` with a "Ghost Border" of `outline` at 10%.

### Input Fields
*   Background: `surface_container_lowest`.
*   Border: "Ghost Border" (1px `outline_variant` at 20%).
*   Focus State: 2px solid `primary_fixed_dim`.

### Cards & Lists
*   **Forbid Divider Lines:** Separate list items using `1.5rem` of vertical whitespace or alternating `surface` and `surface_container_low` backgrounds.
*   **Card Styling:** Use `surface_container_lowest` for the card body. Use `headline-sm` for titles to maintain an editorial feel.

### Additional Signature Component: The "Growth Bar"
*   For progress indicators or data visualizations, use a dual-tone gradient of `tertiary_fixed` to `primary_fixed_dim`. This visualizes "Eco-progress" with a vibrant, living green.

---

## 6. Do’s and Don'ts

### Do
*   **Do** use asymmetrical layouts. Place a large image off-center and let the `display-md` headline overlap it slightly.
*   **Do** use generous whitespace. If you think there is enough space, add 16px more.
*   **Do** use `JetBrains Mono` for small data points or "technical" annotations to lean into the "Tech" aspect of the brand.

### Don't
*   **Don't** use pure #000000. Use `on_surface` (#1b1c19) for text to keep it soft and premium.
*   **Don't** use standard "drop shadows" with high opacity.
*   **Don't** use sharp 0px corners. Always use the Roundedness Scale, favoring `md` (0.75rem) for a friendly yet professional feel.
*   **Don't** use more than one "Vibrant Pulse" (`tertiary_fixed`) element per viewport. It is a beacon, not a coat of paint.