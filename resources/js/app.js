require('./bootstrap');

import React from 'react';
import {render} from 'react-dom';
import {createInertiaApp} from '@inertiajs/inertia-react';
import {InertiaProgress} from '@inertiajs/progress';
import {ChakraProvider, extendTheme} from '@chakra-ui/react';
import {StepsStyleConfig as Steps} from 'chakra-ui-steps';

// does not seem to work for some components, additionally setting color mode
// in layouts
const config = {
    initialColorMode: 'dark',
    useSystemColorMode: false,
};

const components = {
    Steps,
};

const theme = extendTheme({config, components});

const appName =
    window.document.getElementsByTagName('title')[0]?.innerText || 'MyFuture';

createInertiaApp({
    title: title => `${title} - ${appName}`,
    resolve: name => {
        return require(`./Pages/${name}`);
    },
    setup({el, App, props}) {
        return render(
            <>
                <ChakraProvider theme={theme}>
                    <App {...props} />
                </ChakraProvider>
            </>,
            el
        );
    },
});

InertiaProgress.init({color: '#4B5563'});
