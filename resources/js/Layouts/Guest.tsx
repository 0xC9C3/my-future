import React from 'react';
import ApplicationLogo from '@/Components/ApplicationLogo';
import {Link} from '@inertiajs/inertia-react';
import {Center, DarkMode, Flex} from '@chakra-ui/react';

export default function Guest({children}: any) {
    return (
        <DarkMode>
            <Flex justify="center" className="min-h-screen" w="100%">
                <Center flexDirection="column">
                    <Link href="/">
                        <ApplicationLogo className="w-20 h-20 fill-current text-gray-500" />
                    </Link>
                    <div className="w-full sm:max-w-md mt-6 px-6 py-4 shadow-md overflow-hidden sm:rounded-lg">
                        {children}
                    </div>
                </Center>
            </Flex>
        </DarkMode>
    );
}
