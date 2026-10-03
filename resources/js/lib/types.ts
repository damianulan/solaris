export interface ThemeColors {
    [name: string]: string;
}

export type ThemeVariant = 'light' | 'dark';

export interface Theme {
    light: ThemeColors;
    dark: ThemeColors;
    variant: ThemeVariant;
    updateUrl: string;
}

export type TranslationValue = string | TranslationValue[] | { [key: string]: TranslationValue };

export interface Localization {
    locale: string;
    messages: Record<string, TranslationValue>;
}

export interface UserContext {
    name: string;
    email?: string;
}

export interface NavigationLink {
    href: string;
    icon: string;
    title: string;
}
