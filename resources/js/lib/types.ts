export type ThemeColor = string | string[];

export interface ThemeColors {
    [name: string]: ThemeColor;
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

export interface Banner {
    backgroundColor?: string;
    prependIcon?: string;
    text?: string | null;
}

export interface DataTableColumn {
    name: string;
    label: string | null;
    sortable: boolean;
    alignment: 'left' | 'center' | 'right';
    type: 'action' | 'default' | 'selectable';
}

export interface DataTableActionButton {
    color?: string;
    confirm?: string;
    disabled?: boolean;
    href: string;
    icon?: string;
    method?: 'delete' | 'get' | 'patch' | 'post' | 'put';
    title?: string;
}

export interface DataTablePaginator {
    current_page: number;
    data: Record<string, unknown>[];
    per_page: number;
    total: number;
}

export type DataTableFilterValue = string | string[] | null;

export interface DataTableFilterOption {
    label: string;
    value: boolean | number | string;
}

interface DataTableFilterBase {
    label: string;
    slug: string;
}

export interface DataTableTextFilter extends DataTableFilterBase {
    type: 'text';
    value: string | null;
}

export interface DataTableSelectFilter extends DataTableFilterBase {
    options: DataTableFilterOption[];
    type: 'select';
    value: string | null;
}

export interface DataTableMultiselectFilter extends DataTableFilterBase {
    options: DataTableFilterOption[];
    type: 'multiselect';
    value: string[] | null;
}

export type DataTableFilter = DataTableTextFilter | DataTableSelectFilter | DataTableMultiselectFilter;

export interface DataTableFilterDefinition {
    col: 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | 10 | 11 | 12;
    filter: DataTableFilter;
}

export interface DataTableBlueprint {
    columns: DataTableColumn[];
    filters: DataTableFilterDefinition[];
    paginator: DataTablePaginator;
    pagination: number[];
}

export type NavigationElement = NavigationLink | NavigationGroup;
