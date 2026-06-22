import { router, usePage } from '@inertiajs/vue3';
import { currentLocale, loadLanguageAsync } from 'laravel-vue-i18n';
import type { ComputedRef } from 'vue';
import { computed } from 'vue';
import { setUrlDefaults } from '@/wayfinder';

export type UseLocaleReturn = {
    locale: ComputedRef<string>;
    locales: ComputedRef<string[]>;
    updateLocale: (value: string) => Promise<void>;
};

const setCookie = (name: string, value: string, days = 365): void => {
    if (typeof document === 'undefined') {
        return;
    }

    const maxAge = days * 24 * 60 * 60;
    document.cookie = `${name}=${value};path=/;max-age=${maxAge};SameSite=Lax`;
};

const setDocumentLocale = (value: string): void => {
    if (typeof document === 'undefined') {
        return;
    }

    document.documentElement.lang = value.replace('_', '-');
};

export function useLocale(): UseLocaleReturn {
    const page = usePage();
    const locales = computed(() => {
        const value = page.props.locales;

        return Array.isArray(value) ? value : [];
    });

    const locale = currentLocale;

    const buildLocaleUrl = (value: string): string => {
        if (typeof window === 'undefined') {
            return `/${value}`;
        }

        const { pathname, search, hash } = window.location;
        const segments = pathname.split('/').filter(Boolean);
        const available = locales.value;
        const hasLocale =
            segments.length > 0 && available.includes(segments[0]);
        const rest = hasLocale ? segments.slice(1) : segments;
        const nextPath = `/${[value, ...rest].join('/')}`;

        return `${nextPath}${search}${hash}`;
    };

    const updateLocale = async (value: string): Promise<void> => {
        if (typeof window === 'undefined' || value === locale.value) {
            return;
        }

        if (!locales.value.includes(value)) {
            return;
        }

        const resolvedLocale = await loadLanguageAsync(value);
        const nextLocale =
            typeof resolvedLocale === 'string' ? resolvedLocale : value;

        setDocumentLocale(nextLocale);
        setCookie('locale', nextLocale);
        setUrlDefaults({ locale: nextLocale });

        router.visit(buildLocaleUrl(nextLocale), {
            preserveScroll: true,
            preserveState: false,
        });
    };

    return {
        locale,
        locales,
        updateLocale,
    };
}
