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

export interface AuthUser {
    avatarUrl: string | null;
    id: number;
    name: string;
    email: string | null;
    profileUrl: string;
    logoutUrl: string;
}

export interface AuthContext {
    user: AuthUser | null;
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
    text: string;
    color: string;
    icon?: string;
    closeable: boolean
}

export interface Alert extends Snackbar {
    header: string | null;
}

export interface DataTableColumn {
    name: string;
    label: string;
    sortable: boolean;
    alignment: 'left' | 'center' | 'right';
}

export interface DataTablePaginator {
    current_page: number;
    data: Record<string, unknown>[];
    per_page: number;
    total: number;
}

export interface DataTableBlueprint {
    columns: DataTableColumn[];
    paginator: DataTablePaginator;
    pagination: number[];
}

export type NavigationElement = NavigationLink | NavigationGroup;
