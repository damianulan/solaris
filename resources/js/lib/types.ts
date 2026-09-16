export interface ThemeColors {
    [name: string]: string;
}

export interface Theme {
    colors: ThemeColors;
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
