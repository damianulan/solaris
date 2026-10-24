import { createVuetify } from 'vuetify';
import type { JSXComponent } from 'vuetify/lib/util/defineComponent.js';
import { defineComponent, h, type PropType } from 'vue';

import type { Theme, ThemeColor, ThemeColors, ThemeVariant } from '../lib/types';

const gradientStylesheetId = 'vuetify-theme-gradient-stylesheet';

const isGradient = (color: ThemeColor): color is string[] => Array.isArray(color) && color.length > 1;

const normalizeThemeColors = (colors: ThemeColors): Record<string, string> => Object.fromEntries(
    Object.entries(colors).map(([name, color]) => {
        if (Array.isArray(color)) {
            const [firstColor] = color;

            if (!firstColor) {
                throw new Error(`Theme color "${name}" must contain at least one color.`);
            }

            return [name, firstColor];
        }

        return [name, color];
    }),
);

const escapeCssIdentifier = (identifier: string): string => identifier.replace(
    /[^a-zA-Z0-9_-]/g,
    (character) => `\\${character.codePointAt(0)?.toString(16)} `,
);

const createGradientStyles = (variant: ThemeVariant, colors: ThemeColors): string => Object.entries(colors)
    .flatMap(([name, color]) => {
        if (!isGradient(color)) {
            return [];
        }

        const themeClass = `.v-theme--${escapeCssIdentifier(variant)}`;
        const backgroundClass = `.bg-${escapeCssIdentifier(name)}`;
        const textClass = `.text-${escapeCssIdentifier(name)}`;
        const gradient = `linear-gradient(135deg, ${color.join(', ')})`;
        const rules = [
            `${themeClass}${backgroundClass}, ${themeClass} ${backgroundClass} { background-image: ${gradient}; }`,
            `${themeClass}${textClass}, ${themeClass} ${textClass} { background-image: ${gradient}; background-clip: text; -webkit-background-clip: text; color: transparent !important; }`,
        ];

        if (name === 'background') {
            rules.push(`${themeClass} { background-image: ${gradient}; }`);
        }

        return rules;
    })
    .join('\n');

const installGradientStyles = (theme: Theme): void => {
    if (typeof document === 'undefined') {
        return;
    }

    const stylesheet = document.getElementById(gradientStylesheetId) ?? document.createElement('style');

    stylesheet.id = gradientStylesheetId;
    stylesheet.textContent = [
        createGradientStyles('light', theme.light),
        createGradientStyles('dark', theme.dark),
    ].filter(Boolean).join('\n');

    if (!stylesheet.parentNode) {
        document.head.append(stylesheet);
    }
};

const BootstrapIcon = defineComponent({
    name: 'BootstrapIcon',
    props: {
        icon: {
            type: String,
            default: '',
        },
        tag: {
            type: [String, Object] as PropType<string | JSXComponent>,
            required: true,
        },
    },
    setup(props) {
        return () => h(props.tag as string, { class: ['bi', props.icon] });
    },
});

const aliases = {
    collapse: 'bi-chevron-up',
    complete: 'bi-check',
    cancel: 'bi-x-circle',
    close: 'bi-x',
    delete: 'bi-trash-fill',
    clear: 'bi-x-circle',
    success: 'bi-check-circle',
    info: 'bi-info-circle',
    warning: 'bi-exclamation-triangle',
    error: 'bi-exclamation-circle',
    prev: 'bi-chevron-left',
    next: 'bi-chevron-right',
    checkboxOn: 'bi-check-square-fill',
    checkboxOff: 'bi-square',
    checkboxIndeterminate: 'bi-dash-square-fill',
    delimiter: 'bi-circle',
    sortAsc: 'bi-arrow-up',
    sortDesc: 'bi-arrow-down',
    expand: 'bi-chevron-down',
    menu: 'bi-list',
    subgroup: 'bi-chevron-down',
    dropdown: 'bi-chevron-down',
    radioOn: 'bi-record-circle',
    radioOff: 'bi-circle',
    edit: 'bi-pencil',
    ratingEmpty: 'bi-star',
    ratingFull: 'bi-star-fill',
    ratingHalf: 'bi-star-half',
    loading: 'bi-arrow-repeat',
    first: 'bi-chevron-bar-left',
    last: 'bi-chevron-bar-right',
    unfold: 'bi-chevron-double-down',
    file: 'bi-paperclip',
    plus: 'bi-plus',
    minus: 'bi-dash',
    calendar: 'bi-calendar',
    treeviewCollapse: 'bi-caret-down-fill',
    treeviewExpand: 'bi-caret-right-fill',
    eyeDropper: 'bi-eyedropper',
    upload: 'bi-cloud-upload',
    color: 'bi-palette',
    command: 'bi-command',
    ctrl: 'Ctrl',
    space: 'Space',
    shift: 'Shift',
    alt: 'Alt',
    enter: 'Enter',
    arrowup: 'bi-arrow-up',
    arrowdown: 'bi-arrow-down',
    arrowleft: 'bi-arrow-left',
    arrowright: 'bi-arrow-right',
    backspace: 'bi-backspace',
};

const createVuetifyPlugin = (theme: Theme) => {
    installGradientStyles(theme);

    return createVuetify({
        ssr: true,
        theme: {
            defaultTheme: theme.variant,
            themes: {
                light: {
                    dark: false,
                    colors: normalizeThemeColors(theme.light),
                },
                dark: {
                    dark: true,
                    colors: normalizeThemeColors(theme.dark),
                },
            },
            variations: {
                colors: ['primary', 'secondary', 'accent', 'surface'],
                lighten: 5,
                darken: 5,
            },
        },
        icons: {
            defaultSet: 'bootstrap',
            aliases,
            sets: {
                bootstrap: {
                    component: BootstrapIcon,
                },
            },
        },
    });
};

export default createVuetifyPlugin;
