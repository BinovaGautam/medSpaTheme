/**
 * Contact Page Selector Finder
 * Run this in browser console on your ACTUAL contact page
 */

console.log('🔍 CONTACT PAGE STRUCTURE ANALYSIS');
console.log('==========================================');

// Check if we're actually on a contact page
const url = window.location.href;
const isContactPage = url.includes('contact') ||
                     document.body.classList.contains('contact') ||
                     document.title.toLowerCase().includes('contact');

console.log('📍 Current URL:', url);
console.log('📍 Is Contact Page?', isContactPage);

// Get all body classes
const bodyClasses = Array.from(document.body.classList);
console.log('📋 Body Classes:', bodyClasses);

// Find all buttons on the page
const allButtons = document.querySelectorAll('button, input[type="submit"], input[type="button"], .btn, [class*="btn"], .button, [class*="button"]');
console.log(`🔍 Found ${allButtons.length} button elements`);

// Analyze each button
allButtons.forEach((btn, index) => {
    const classes = btn.className;
    const id = btn.id;
    const tagName = btn.tagName.toLowerCase();
    const text = btn.textContent?.trim().substring(0, 50) || 'No text';
    const computedStyle = getComputedStyle(btn);
    const bgColor = computedStyle.backgroundColor;
    const textColor = computedStyle.color;

    console.log(`\n🔘 Button ${index + 1}:`);
    console.log(`   Tag: ${tagName}`);
    console.log(`   Classes: "${classes}"`);
    console.log(`   ID: "${id}"`);
    console.log(`   Text: "${text}"`);
    console.log(`   Background: ${bgColor}`);
    console.log(`   Text Color: ${textColor}`);

    // Generate specific selector
    let selector = tagName;
    if (id) selector += `#${id}`;
    if (classes) selector += `.${classes.split(' ').filter(c => c).join('.')}`;

    console.log(`   Selector: ${selector}`);
});

// Check for form containers
const forms = document.querySelectorAll('form');
console.log(`\n📝 Found ${forms.length} forms`);

forms.forEach((form, index) => {
    const classes = form.className;
    const id = form.id;
    const action = form.action;

    console.log(`\n📝 Form ${index + 1}:`);
    console.log(`   Classes: "${classes}"`);
    console.log(`   ID: "${id}"`);
    console.log(`   Action: "${action}"`);

    const formButtons = form.querySelectorAll('button, input[type="submit"], input[type="button"]');
    console.log(`   Buttons in form: ${formButtons.length}`);
});

// Check what CSS custom properties are actually available
const rootStyle = getComputedStyle(document.documentElement);
const tokenProps = [
    '--component-bg-color-primary',
    '--component-text-color-primary',
    '--color-primary',
    '--palette-primary',
    '--btn-primary-bg',
    '--customizer-enhancements-loaded'
];

console.log('\n🎨 CSS CUSTOM PROPERTIES:');
tokenProps.forEach(prop => {
    const value = rootStyle.getPropertyValue(prop).trim();
    console.log(`   ${prop}: ${value || 'NOT SET'}`);
});

// Generate CSS rules for actual buttons found
console.log('\n🛠️ GENERATED CSS RULES:');
console.log('/* Copy these rules to tokenization-contact-overrides.css */\n');

const uniqueSelectors = new Set();
allButtons.forEach(btn => {
    const classes = btn.className;
    const id = btn.id;
    const tagName = btn.tagName.toLowerCase();

    let selector = tagName;
    if (id) selector += `#${id}`;
    if (classes) {
        const classList = classes.split(' ').filter(c => c && c.length > 0);
        if (classList.length > 0) {
            selector += `.${classList.join('.')}`;
        }
    }

    if (!uniqueSelectors.has(selector)) {
        uniqueSelectors.add(selector);
        console.log(`${selector} {`);
        console.log(`    background-color: var(--component-bg-color-primary) !important;`);
        console.log(`    color: var(--component-text-color-primary) !important;`);
        console.log(`    border-color: var(--component-border-color-primary) !important;`);
        console.log(`}\n`);
    }
});

// Check current Visual Customizer state
console.log('\n🎨 VISUAL CUSTOMIZER STATE:');
const vcSettings = localStorage.getItem('visual_customizer_settings');
if (vcSettings) {
    try {
        const parsed = JSON.parse(vcSettings);
        console.log('   Current Settings:', parsed);
    } catch (e) {
        console.log('   Settings Parse Error:', e);
    }
} else {
    console.log('   No Visual Customizer settings found');
}

console.log('\n✅ ANALYSIS COMPLETE');
console.log('Copy the CSS rules above and add them to your tokenization CSS file.');
