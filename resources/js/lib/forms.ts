export type FormMethod = 'patch' | 'post' | 'put';

export type FormValue = boolean | File | File[] | null | number | string;

export type FormValues = Record<string, FormValue>;

interface FormItemBase<TType extends string, TValue extends FormValue> {
    autocomplete?: string;
    columns?: number;
    defaultValue: TValue;
    hint?: string;
    label: string;
    name: string;
    placeholder?: string;
    type: TType;
}

interface FormTextItem extends FormItemBase<'color' | 'email' | 'password' | 'search' | 'tel' | 'text' | 'url', string> {
}

interface FormTextareaItem extends FormItemBase<'textarea', string> {
    rows?: number;
}

export interface FormOption {
    label: string;
    value: string;
}

interface FormSelectItem extends FormItemBase<'select', null | string> {
    options: readonly FormOption[];
}

interface FormChoiceItem extends FormItemBase<'radio', null | string> {
    inline?: boolean;
    options: readonly FormOption[];
}

interface FormBooleanItem extends FormItemBase<'checkbox' | 'switch', boolean> {
    color?: string;
    inset?: boolean;
}

interface FormNumberItem extends FormItemBase<'number', null | number> {
    max?: number;
    min?: number;
    step?: number;
}

interface FormRangeItem extends FormItemBase<'range', number> {
    max: number;
    min: number;
    showTicks?: boolean | 'always';
    step?: number;
    thumbLabel?: boolean | 'always' | 'hover';
}

interface FormDateItem extends FormItemBase<'date' | 'datetime-local' | 'month' | 'time' | 'week', null | string> {
    max?: string;
    min?: string;
}

interface FormFileItem extends FormItemBase<'file', File | File[] | null> {
    accept?: string;
    multiple?: boolean;
    showSize?: boolean;
}

export type FormItem =
    | FormBooleanItem
    | FormChoiceItem
    | FormDateItem
    | FormFileItem
    | FormNumberItem
    | FormRangeItem
    | FormSelectItem
    | FormTextareaItem
    | FormTextItem;

export interface FormSchema {
    action: string;
    items: readonly FormItem[];
    method: FormMethod;
    resetLabel?: string;
    submitLabel?: string;
    successMessage?: string;
}
