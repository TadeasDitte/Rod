<script setup lang="ts">
import { Globe } from '@lucide/vue';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuRadioGroup,
    DropdownMenuRadioItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { useLocale } from '@/composables/useLocale';

const { locale, locales, updateLocale } = useLocale();

const localeLabels: Record<string, string> = {
    sk: 'SK',
    cz: 'CZ',
    en: 'EN',
};

const formatLocale = (value: string): string => {
    return localeLabels[value] ?? value.toUpperCase();
};

const selectedLocale = computed({
    get: () => locale.value,
    set: (value: string) => {
        void updateLocale(value);
    },
});
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" size="sm" class="gap-1.5">
                <Globe class="size-4" />
                <span class="text-sm font-medium">{{
                    formatLocale(locale)
                }}</span>
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" class="min-w-24">
            <DropdownMenuSeparator />
            <DropdownMenuRadioGroup v-model="selectedLocale">
                <DropdownMenuRadioItem
                    v-for="language in locales"
                    :key="language"
                    :value="language"
                >
                    {{ formatLocale(language) }}
                </DropdownMenuRadioItem>
            </DropdownMenuRadioGroup>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
