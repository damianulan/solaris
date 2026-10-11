import { usePage } from '@inertiajs/vue3';

import type { TranslationValue } from './types';

type TranslationMessages = Record<string, TranslationValue>;
type TranslationReplacements = Record<string, number | string>;

const resolveTranslation = (messages: TranslationMessages, key: string): TranslationValue | undefined => {
    return key.split('.').reduce<TranslationValue | undefined>((value, segment) => {
        if (value === undefined || typeof value === 'string' || Array.isArray(value)) {
            return undefined;
        }

        return value[segment];
    }, messages);
};

export const translateMessage = (
    messages: TranslationMessages,
    key: string,
    replacements: TranslationReplacements = {},
): string => {
    const value = resolveTranslation(messages, key);

    if (typeof value !== 'string') {
        return key;
    }

    return Object.entries(replacements).reduce((translation, [placeholder, replacement]) => {
        return translation.split(`:${placeholder}`).join(String(replacement));
    }, value);
};

export const useTranslations = () => {
    const page = usePage();

    return {
        translate: (key: string, replacements: TranslationReplacements = {}): string => {
            return translateMessage(page.props.localization.messages, key, replacements);
        },
    };
};
