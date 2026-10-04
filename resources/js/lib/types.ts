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
    active: boolean;
    icon: string | null;
    link: string;
    title: string;
    disabled: boolean;
}

export interface NavigationGroup {
    icon: string | null;
    items: NavigationElement[];
    priority: number;
    title: string;
    active: boolean;
    disabled: boolean;
}

export interface Page {
    title: string;
    header: string | null;
    description: string | null;
}

export interface Snackbar {
    message: string;
    color: string;
}

export type NavigationElement = NavigationLink | NavigationGroup;
