import React from 'react';
import Authenticated from '@/Layouts/Authenticated';
import {Head, Link as InertiaLink} from '@inertiajs/inertia-react';
import {Flex, Box, VStack, SlideFade} from '@chakra-ui/react';
import {EmailIcon} from '@chakra-ui/icons';

export default function Dashboard(props: any) {
    const routes = [
        {
            text: 'Overview',
            name: 'dashboard.overview',
        },
        {
            text: 'Applications',
            name: 'dashboard.applications',
        },
        {
            text: 'Templates',
            name: 'dashboard.templates',
        },
        {
            text: 'Respondents',
            name: 'dashboard.respondents',
        },
        {
            text: 'Settings',
            name: 'dashboard.settings',
        },
        {
            text: 'Account',
            name: 'dashboard.account',
        },
    ];

    return (
        <Authenticated auth={props.auth} errors={props.errors}>
            <Head title="Dashboard" />

            <Flex className="h-full">
                <VStack spacing={4} align="stretch">
                    <InertiaLink
                        className={
                            route().current('dashboard.apply')
                                ? 'rounded border border-green-400 bg-green-400 p-3 w-full flex'
                                : 'transition ease-in-out duration-500 rounded border border-green-400 hover:bg-green-400 p-3 flex'
                        }
                        href={route('dashboard.apply')}
                        disabled={route().current('dashboard.apply')}
                    >
                        Apply
                        <Flex
                            className="w-full h-full"
                            justifyContent="center"
                            alignItems="center"
                        >
                            <EmailIcon w={6} h={6} />
                        </Flex>
                    </InertiaLink>
                    {routes.map((dashboardRoute, index) => {
                        return (
                            <InertiaLink
                                key={index}
                                className={
                                    route().current(dashboardRoute.name)
                                        ? 'rounded bg-green-400 p-3'
                                        : 'transition ease-in-out duration-500 rounded hover:bg-green-400 p-3'
                                }
                                href={route(dashboardRoute.name)}
                                disabled={route().current(dashboardRoute.name)}
                            >
                                <Box>{dashboardRoute.text}</Box>
                            </InertiaLink>
                        );
                    })}
                </VStack>
                <Box className="w-full">
                    <SlideFade
                        className="w-10/12 mx-auto h-full flex flex-col"
                        in
                    >
                        {props.children}
                    </SlideFade>
                </Box>
            </Flex>
        </Authenticated>
    );
}
