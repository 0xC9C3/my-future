import React from 'react';
import ApplicationLogo from '@/Components/ApplicationLogo';
import {Link} from '@inertiajs/inertia-react';
import {
    Menu,
    MenuButton,
    Button,
    MenuList,
    MenuItem,
    DarkMode,
    Flex,
    Spacer,
} from '@chakra-ui/react';
import {ChevronDownIcon} from '@chakra-ui/icons';

export default function Authenticated({auth, children}: any) {
    return (
        <DarkMode>
            <div className="min-h-screen h-screen w-9/12 mx-auto flex flex-col">
                <Flex className="px-4 py-12">
                    <Link href={route('dashboard')}>
                        <ApplicationLogo className="w-10 h-10" />
                    </Link>
                    <Spacer />
                    <Menu>
                        <MenuButton as={Button} rightIcon={<ChevronDownIcon />}>
                            {auth.user.email}
                        </MenuButton>
                        <MenuList>
                            <MenuItem as="div">
                                <Link
                                    href={route('logout')}
                                    method="post"
                                    as="button"
                                    className="text-left w-full"
                                >
                                    Log Out
                                </Link>
                            </MenuItem>
                        </MenuList>
                    </Menu>
                </Flex>

                <main className="h-full">{children}</main>

                <footer className="py-6 text-center">MyFuture</footer>
            </div>
        </DarkMode>
    );
}
