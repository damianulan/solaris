<script lang="ts" setup>
type ComponentSize = "xs" | "sm" | "md" | "lg";

interface IProps {
    text?: string;
    icon?: false | string | undefined;
    color?: string | undefined;
    size?: ComponentSize;
    type?: "error" | "success" | "info" | "warning" | undefined;
    variant?: "tonal" | "outlined" | "plain" | "text" | "flat";
}

const SIZE_CLASSES: Record<ComponentSize, string> = {
    xs: "text-body-medium",
    sm: "text-body-large",
    md: "text-title-medium",
    lg: "text-title-large",
};

const props = withDefaults(defineProps<IProps>(), {
    text: "",
    icon: false,
    size: "xs",
    color: undefined,
    type: "warning",
    variant: "plain",
});
</script>
<template>
    <v-alert
        density="compact"
        :icon="props.icon"
        :type="props.type"
        :variant="props.variant"
        class="font-weight-semibold pa-0 pl-5"
        :class="SIZE[props.size]"
        :color="props.color"
        prominent
        icon-size="small"
        :rounded="false"
        border="start"
    >
        <slot>{{ props.text }}</slot>
    </v-alert>
</template>
