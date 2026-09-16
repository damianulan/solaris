import { createVuetify } from 'vuetify';
import type { JSXComponent } from 'vuetify/lib/util/defineComponent.js';
import { defineComponent, h, type PropType } from 'vue';

export type ThemeColors = Record<string, string>;

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
    delete: 'bi-trash',
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

export const createVuetifyPlugin = (colors: ThemeColors) => createVuetify({
    theme: {
        defaultTheme: 'solaris',
        themes: {
            solaris: {
                dark: false,
                colors,
            },
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
