<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { FileText, Network, ScrollText, ShieldCheck } from '@lucide/vue';
import { trans } from 'laravel-vue-i18n';
import Languages from '@/components/Languages.vue';
import { Button } from '@/components/ui/button';
import { dashboard, login, register } from '@/routes';
withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head :title="trans('welcome.title')" />

    <div class="flex min-h-svh flex-col bg-background text-foreground">
        <!-- Header -->
        <header
            class="sticky top-0 z-50 border-b border-border/60 bg-background/80 backdrop-blur-md"
        >
            <div
                class="mx-auto flex h-16 max-w-5xl items-center justify-between px-6"
            >
                <!-- Logo -->
                <Link href="/" class="flex items-center gap-2">
                    <span class="text-lg font-bold tracking-tight">Rod</span>
                </Link>

                <!-- Nav -->
                <nav class="flex items-center gap-2 text-sm sm:gap-6">
                    <a
                        href="#features"
                        class="hidden text-muted-foreground transition-colors hover:text-foreground sm:inline"
                    >
                        {{ $t('welcome.nav_features') }}
                    </a>
                    <a
                        href="#about"
                        class="hidden text-muted-foreground transition-colors hover:text-foreground sm:inline"
                    >
                        {{ $t('welcome.nav_about') }}
                    </a>
                    <Languages />

                    <template v-if="$page.props.auth.user">
                        <Button as-child variant="default" size="sm">
                            <Link :href="dashboard()">
                                {{ $t('common.dashboard') }}
                            </Link>
                        </Button>
                    </template>
                    <template v-else>
                        <Button as-child variant="ghost" size="sm">
                            <Link :href="login()">
                                {{ $t('common.log_in') }}
                            </Link>
                        </Button>
                        <Button
                            v-if="canRegister"
                            as-child
                            variant="default"
                            size="sm"
                        >
                            <Link :href="register()">
                                {{ $t('common.register') }}
                            </Link>
                        </Button>
                    </template>
                </nav>
            </div>
        </header>

        <!-- Hero -->
        <section
            class="flex flex-1 flex-col items-center justify-center px-6 py-24 text-center sm:py-32"
        >
            <div class="mx-auto max-w-2xl space-y-6">
                <h1 class="text-4xl font-bold tracking-tight sm:text-5xl">
                    {{ $t('welcome.tagline') }}
                </h1>
                <p class="mx-auto max-w-xl text-lg text-muted-foreground">
                    {{ $t('welcome.subtitle') }}
                </p>
                <div
                    class="flex flex-col items-center justify-center gap-3 sm:flex-row"
                >
                    <template v-if="$page.props.auth.user">
                        <Button as-child size="lg">
                            <Link :href="dashboard()">
                                {{ $t('welcome.go_to_dashboard') }}
                            </Link>
                        </Button>
                    </template>
                    <template v-else>
                        <Button v-if="canRegister" as-child size="lg">
                            <Link :href="register()">
                                {{ $t('welcome.get_started') }}
                            </Link>
                        </Button>
                        <Button as-child variant="outline" size="lg">
                            <Link :href="login()">
                                {{ $t('common.log_in') }}
                            </Link>
                        </Button>
                    </template>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section id="features" class="border-t border-border/60 px-6 py-20">
            <div class="mx-auto max-w-5xl">
                <div class="mx-auto max-w-2xl text-center">
                    <p class="text-sm font-medium text-muted-foreground">
                        {{ $t('welcome.features_badge') }}
                    </p>
                    <h2 class="mt-2 text-3xl font-bold tracking-tight">
                        {{ $t('welcome.features_title') }}
                    </h2>
                    <p class="mt-3 text-muted-foreground">
                        {{ $t('welcome.features_description') }}
                    </p>
                </div>

                <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Feature: Trees -->
                    <div class="space-y-3">
                        <div
                            class="flex size-10 items-center justify-center rounded-lg border border-border bg-muted/40"
                        >
                            <Network class="size-5 text-foreground" />
                        </div>
                        <h3 class="font-semibold">
                            {{ $t('welcome.feature_trees_title') }}
                        </h3>
                        <p
                            class="text-sm leading-relaxed text-muted-foreground"
                        >
                            {{ $t('welcome.feature_trees_description') }}
                        </p>
                    </div>

                    <!-- Feature: Stories -->
                    <div class="space-y-3">
                        <div
                            class="flex size-10 items-center justify-center rounded-lg border border-border bg-muted/40"
                        >
                            <ScrollText class="size-5 text-foreground" />
                        </div>
                        <h3 class="font-semibold">
                            {{ $t('welcome.feature_stories_title') }}
                        </h3>
                        <p
                            class="text-sm leading-relaxed text-muted-foreground"
                        >
                            {{ $t('welcome.feature_stories_description') }}
                        </p>
                    </div>

                    <!-- Feature: Sources -->
                    <div class="space-y-3">
                        <div
                            class="flex size-10 items-center justify-center rounded-lg border border-border bg-muted/40"
                        >
                            <FileText class="size-5 text-foreground" />
                        </div>
                        <h3 class="font-semibold">
                            {{ $t('welcome.feature_sources_title') }}
                        </h3>
                        <p
                            class="text-sm leading-relaxed text-muted-foreground"
                        >
                            {{ $t('welcome.feature_sources_description') }}
                        </p>
                    </div>

                    <!-- Feature: Privacy -->
                    <div class="space-y-3">
                        <div
                            class="flex size-10 items-center justify-center rounded-lg border border-border bg-muted/40"
                        >
                            <ShieldCheck class="size-5 text-foreground" />
                        </div>
                        <h3 class="font-semibold">
                            {{ $t('welcome.feature_privacy_title') }}
                        </h3>
                        <p
                            class="text-sm leading-relaxed text-muted-foreground"
                        >
                            {{ $t('welcome.feature_privacy_description') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section id="about" class="border-t border-border/60 px-6 py-20">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight">
                    {{ $t('welcome.cta_title') }}
                </h2>
                <p class="mt-3 text-muted-foreground">
                    {{ $t('welcome.cta_description') }}
                </p>
                <div class="mt-8 flex justify-center">
                    <template v-if="$page.props.auth.user">
                        <Button as-child size="lg">
                            <Link :href="dashboard()">
                                {{ $t('welcome.go_to_dashboard') }}
                            </Link>
                        </Button>
                    </template>
                    <template v-else>
                        <Button v-if="canRegister" as-child size="lg">
                            <Link :href="register()">
                                {{ $t('welcome.cta_button') }}
                            </Link>
                        </Button>
                        <Button v-else as-child size="lg">
                            <Link :href="login()">
                                {{ $t('common.log_in') }}
                            </Link>
                        </Button>
                    </template>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-border/60 px-6 py-8">
            <div
                class="mx-auto flex max-w-5xl flex-col items-center justify-between gap-4 text-sm text-muted-foreground sm:flex-row"
            >
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-foreground">Rod</span>
                    <span class="text-muted-foreground/60">&mdash;</span>
                    <span>{{ $t('welcome.footer_description') }}</span>
                </div>
                <p>
                    &copy; {{ new Date().getFullYear() }}
                    {{ $t('welcome.footer_rights') }}
                </p>
            </div>
        </footer>
    </div>
</template>
