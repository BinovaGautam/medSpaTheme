# 🧩 **COMPONENT LIBRARY VISUAL DESIGN**
## **PreetiDreams Medical Spa - UI Component Specifications**
### **Visual Component Library Following UI/UX Creation Workflow**

---

## **🎯 HERO COMPONENTS**

### **🌟 HERO CONTENT COMPONENT**

```
┌─────────────────────────────────────────────────────────────┐
│                                                             │
│                    🌟 PREETIDREAMS                          │
│                                                             │
│              Where Medical Artistry                         │
│                   Meets Luxury                              │
│                                                             │
│        Discover your perfect treatment with our             │
│        personalized approach to medical aesthetics          │
│                                                             │
│   🏆 Board      ⭐ Award       👥 2000+      🔒 HIPAA      │
│      Certified     Winning        Patients     Compliant    │
│                                                             │
│              [Book Consultation Now]                        │
│                                                             │
└─────────────────────────────────────────────────────────────┘

SPECIFICATIONS:
- Background: Linear gradient (Sage to Gold)
- Text Color: White (#FFFFFF)
- Title Font: Playfair Display, 48px, Bold
- Subtitle Font: Inter, 20px, Regular
- Trust Indicators: 4 items, horizontal flex
- CTA Button: Gold background, white text
```

### **🎯 QUIZ COMPONENT**

```
┌─────────────────────────────────────────────────────────────┐
│                                                             │
│               🎯 FIND YOUR TREATMENT                        │
│                                                             │
│         Answer a few questions to get                       │
│           personalized recommendations                      │
│                                                             │
│         ● ○ ○ ○ ○  [Progress Indicators]                    │
│                                                             │
│         What's your primary concern?                        │
│                                                             │
│   ┌─────────────────┐    ┌─────────────────┐               │
│   │  💉 Anti-Aging  │    │ ✨ Skin Texture │               │
│   └─────────────────┘    └─────────────────┘               │
│                                                             │
│   ┌─────────────────┐    ┌─────────────────┐               │
│   │  🌊 Hydration   │    │ 💪 Body Sculpt  │               │
│   └─────────────────┘    └─────────────────┘               │
│                                                             │
│            [← Back]         [Continue →]                   │
│                                                             │
└─────────────────────────────────────────────────────────────┘

SPECIFICATIONS:
- Background: Glassmorphic (white 10%, blur 25px)
- Border: 1px solid white 20%
- Border Radius: 16px
- Quiz Pills: 2x2 grid on desktop, 1 column mobile
- Progress Dots: 5 indicators, active state highlighted
```

---

## **🎭 SERVICE COMPONENTS**

### **🌟 SERVICE CATEGORY CARD**

```
┌─────────────────────────────────────────────────────────────┐
│ ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ │ ← Gradient Top Border
│                                                             │
│                         💉                                  │
│                Injectable Artistry                          │
│                                                             │
│        Enhance your natural beauty with precision           │
│        injectable treatments for wrinkle reduction          │
│               and volume restoration.                       │
│                                                             │
│        ✓ Botox / Fillers                                   │
│                                                             │
│              [Explore Treatments →]                         │
│                                                             │
└─────────────────────────────────────────────────────────────┘

SPECIFICATIONS:
- Background: White (#FFFFFF)
- Box Shadow: 0 4px 12px rgba(0,0,0,0.1)
- Border Radius: 12px
- Padding: 32px
- Icon: 48px emoji, centered
- Title: Playfair Display, 24px, Bold
- Description: Inter, 16px, Regular
- Hover: Lift -4px, shadow increase
```

### **🎯 SERVICE GRID LAYOUT**

```
MOBILE (1 Column):
┌─────────────────────────────────────┐
│ [Injectable Artistry Card]          │
├─────────────────────────────────────┤
│ [Facial Renaissance Card]           │
├─────────────────────────────────────┤
│ [Precision Treatments Card]         │
└─────────────────────────────────────┘

DESKTOP (3 Columns):
┌─────────────┬─────────────┬─────────────┐
│[Injectable] │[Facial Ren] │[Precision]  │
├─────────────┼─────────────┼─────────────┤
│[Regenerat.] │[Wellness]   │[Artistry]   │
├─────────────┼─────────────┼─────────────┤
│[Laser]      │[Carbon]     │[Body Sculpt]│
└─────────────┴─────────────┴─────────────┘

SPECIFICATIONS:
- Grid Gap: 24px mobile, 32px desktop
- Max Width: 1200px
- Container Padding: 16px mobile, 32px desktop
```

---

## **🏆 TRUST COMPONENTS**

### **🌟 WHY CHOOSE US CARD**

```
┌─────────────────────────────────────────────────────────────┐
│                                                             │
│                         🏆                                  │
│                   Board Certified                           │
│                                                             │
│              Expert medical professionals                    │
│              with advanced training in                      │
│              aesthetic medicine and                         │
│                  patient safety                             │
│                                                             │
└─────────────────────────────────────────────────────────────┘

SPECIFICATIONS:
- Background: White (#FFFFFF)
- Box Shadow: 0 2px 8px rgba(0,0,0,0.08)
- Border Radius: 12px
- Padding: 24px
- Text Align: Center
- Icon: 36px emoji
- Title: Inter, 20px, Bold
- Description: Inter, 14px, Regular
```

### **🎯 TRUST GRID LAYOUT**

```
MOBILE (1 Column):
┌─────────────────────────────────────┐
│ [Board Certified]                   │
├─────────────────────────────────────┤
│ [Award Winning]                     │
├─────────────────────────────────────┤
│ [2000+ Patients]                    │
├─────────────────────────────────────┤
│ [HIPAA Compliant]                   │
└─────────────────────────────────────┘

DESKTOP (4 Columns):
┌─────────┬─────────┬─────────┬─────────┐
│[Board   │[Award   │[2000+   │[HIPAA   │
│Certif.] │Winning] │Patient] │Compli.] │
└─────────┴─────────┴─────────┴─────────┘

SPECIFICATIONS:
- Grid Gap: 20px mobile, 24px desktop
- Equal height cards
- Responsive breakpoint: 768px
```

---

## **💬 TESTIMONIAL COMPONENTS**

### **🌟 TESTIMONIAL CARD**

```
┌─────────────────────────────────────────────────────────────┐
│ "                                                           │
│                                                             │
│     "The results exceeded my expectations. Dr. Preeti       │
│     and her team provided exceptional care and the          │
│     most natural-looking results I could have asked         │
│     for. Highly recommend!"                                 │
│                                                             │
│     ┌─────┐  Sarah M.                            ⭐⭐⭐⭐⭐    │
│     │ SM  │  Injectable Artistry Patient                   │
│     └─────┘                                                 │
│                                                             │
└─────────────────────────────────────────────────────────────┘

SPECIFICATIONS:
- Background: White (#FFFFFF)
- Box Shadow: 0 4px 16px rgba(0,0,0,0.1)
- Border Radius: 16px
- Padding: 32px
- Quote Mark: Large serif font, accent color
- Text: Inter, 18px, Italic
- Author: Inter, 16px, Bold
- Treatment: Inter, 14px, Regular, Gray
- Rating: Gold stars, 20px
```

---

## **📞 CTA COMPONENTS**

### **🌟 CONSULTATION INVITATION**

```
┌─────────────────────────────────────────────────────────────┐
│                                                             │
│              Ready to Begin Your Journey?                   │
│                                                             │
│        Schedule your complimentary consultation             │
│            and discover your personalized                   │
│               treatment plan today                          │
│                                                             │
│   [Schedule Consultation]    [Call (555) 123-4567]        │
│                                                             │
│   ✓ Complimentary    ✓ No Obligation    ✓ Expert Advice   │
│                                                             │
└─────────────────────────────────────────────────────────────┘

SPECIFICATIONS:
- Background: Linear gradient (Gold to Sage)
- Text Color: White (#FFFFFF)
- Title: Playfair Display, 36px, Bold
- Description: Inter, 20px, Regular
- Primary Button: White background, dark text
- Secondary Button: Transparent, white border
- Features: 3 items, checkmark icons
```

---

## **🔘 BUTTON COMPONENTS**

### **🌟 BUTTON VARIATIONS**

```
PRIMARY BUTTONS:
┌─────────────────┐ ┌─────────────────┐ ┌─────────────────┐
│ Book Now        │ │ Book Now        │ │ Book Now        │
│ [Default]       │ │ [Hover]         │ │ [Focus]         │
│ Sage Green      │ │ Darker Sage     │ │ Blue Outline    │
│ White Text      │ │ White Text      │ │ White Text      │
└─────────────────┘ └─────────────────┘ └─────────────────┘

SECONDARY BUTTONS:
┌─────────────────┐ ┌─────────────────┐ ┌─────────────────┐
│ Learn More      │ │ Learn More      │ │ Learn More      │
│ [Default]       │ │ [Hover]         │ │ [Focus]         │
│ Transparent     │ │ Light Fill      │ │ Blue Outline    │
│ Sage Border     │ │ Sage Text       │ │ Sage Text       │
└─────────────────┘ └─────────────────┘ └─────────────────┘

SPECIFICATIONS:
- Border Radius: 8px (medium), 12px (large)
- Padding: 12px 24px (medium), 16px 32px (large)
- Font: Inter, 16px, Semibold
- Transition: 200ms ease
- Min Height: 44px (touch compliance)
```

---

## **📱 RESPONSIVE COMPONENT BEHAVIOR**

### **🌟 BREAKPOINT TRANSFORMATIONS**

```
HERO COMPONENT:
Mobile:    [Stacked Layout - Content Above Quiz]
Tablet:    [Stacked Layout - Content Above Quiz]
Desktop:   [Side-by-Side - Content | Quiz]

SERVICE GRID:
Mobile:    [1 Column Grid]
Tablet:    [2 Column Grid]
Desktop:   [3 Column Grid]

TRUST INDICATORS:
Mobile:    [1 Column Stack]
Tablet:    [2x2 Grid]
Desktop:   [4 Column Row]

TESTIMONIALS:
Mobile:    [1 Column Stack]
Tablet:    [2 Column Grid]
Desktop:   [3 Column Grid]
```

### **🎯 SPACING SYSTEM**

```
SPACING SCALE:
┌─────────────────────────────────────────────────────────────┐
│ XS:  4px   ●                                               │
│ SM:  8px   ●●                                              │
│ MD: 16px   ●●●●                                            │
│ LG: 24px   ●●●●●●                                          │
│ XL: 32px   ●●●●●●●●                                        │
│ 2XL: 48px  ●●●●●●●●●●●●                                    │
│ 3XL: 64px  ●●●●●●●●●●●●●●●●                                │
│ 4XL: 96px  ●●●●●●●●●●●●●●●●●●●●●●●●                        │
└─────────────────────────────────────────────────────────────┘

USAGE:
- Component Padding: LG (24px) mobile, XL (32px) desktop
- Section Spacing: 3XL (64px) mobile, 4XL (96px) desktop
- Grid Gaps: LG (24px) mobile, XL (32px) desktop
- Element Margins: SM (8px) to LG (24px)
```

---

## **🎨 VISUAL DESIGN TOKENS**

### **🌟 COLOR TOKENS VISUAL**

```
PRIMARY PALETTE:
┌─────────────┐ ┌─────────────┐ ┌─────────────┐
│ --primary   │ │ --secondary │ │ --accent    │
│ #6B8E6B     │ │ #8BAE8B     │ │ #D4AF37     │
│ Sage Green  │ │ Light Sage  │ │ Luxury Gold │
└─────────────┘ └─────────────┘ └─────────────┘

NEUTRAL PALETTE:
┌─────────────┐ ┌─────────────┐ ┌─────────────┐
│ --background│ │ --surface   │ │ --border    │
│ #FEFEFE     │ │ #FFFFFF     │ │ #E5E5E5     │
│ Off White   │ │ Pure White  │ │ Light Gray  │
└─────────────┘ └─────────────┘ └─────────────┘

TEXT PALETTE:
┌─────────────┐ ┌─────────────┐ ┌─────────────┐
│ --text-pri  │ │ --text-sec  │ │ --text-inv  │
│ #2D3748     │ │ #718096     │ │ #FFFFFF     │
│ Dark Gray   │ │ Medium Gray │ │ Pure White  │
└─────────────┘ └─────────────┘ └─────────────┘
```

### **✨ TYPOGRAPHY TOKENS VISUAL**

```
FONT FAMILIES:
┌─────────────────────────────────────────────────────────────┐
│ --font-primary: "Playfair Display", serif                  │
│ Usage: Headlines, section titles, luxury emphasis          │
│                                                             │
│ --font-secondary: "Inter", sans-serif                      │
│ Usage: Body text, buttons, UI elements                     │
└─────────────────────────────────────────────────────────────┘

FONT SIZES:
┌─────────────────────────────────────────────────────────────┐
│ --text-display: 48px    │ Hero headlines                   │
│ --text-4xl: 36px        │ Major section headers            │
│ --text-3xl: 30px        │ Section headers                  │
│ --text-2xl: 24px        │ Card titles                      │
│ --text-xl: 20px         │ Subsection headers               │
│ --text-lg: 18px         │ Large body text                  │
│ --text-base: 16px       │ Standard body text               │
│ --text-sm: 14px         │ Small text, captions             │
└─────────────────────────────────────────────────────────────┘
```

---

## **✅ COMPONENT LIBRARY COMPLETION**

### **📋 COMPONENT INVENTORY**

- ✅ **Hero Components** - Content + Quiz integration
- ✅ **Service Components** - Category cards + grid layouts
- ✅ **Trust Components** - Why choose us cards
- ✅ **Testimonial Components** - Patient review cards
- ✅ **CTA Components** - Consultation invitation
- ✅ **Button Components** - All states and variations
- ✅ **Responsive Behavior** - All breakpoint transformations
- ✅ **Design Tokens** - Complete visual specifications

### **🎯 READY FOR IMPLEMENTATION**

This component library provides:
- **Visual Specifications** for all UI elements
- **Responsive Behavior** documentation
- **Accessibility Features** built into each component
- **Design Token Integration** for consistency
- **Developer Handoff** ready documentation

**All components are designed following the UI/UX creation workflow requirements and are ready for development implementation.** 
